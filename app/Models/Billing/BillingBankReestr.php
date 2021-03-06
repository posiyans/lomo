<?php

namespace App\Models\Billing;

use App\Models\Stead;
use Illuminate\Database\Eloquent\Model;

class BillingBankReestr extends Model
{
    protected $casts = [
        'data' => 'array',
//        'file_publish' => 'boolean'
    ];


    protected $columnLabel = [
        0 => 'Дата',
        1 => 'Время',
        2 => 'Банк1',
        3 => 'Банк2',
        4 => '№ транзакции',
        5 => '№ участка',
        6 => 'ФИО',
        7 => 'Назаначение',
        8 => 'Сумма',
        9 => 'Сумма с НДС',
        10 => 'НДС',
        11 => 'Номер',
    ];



    public function parseData(array $raw = [])
    {
        $rez = [];
        if (count($raw) == 0 && isset($this->data['raw'])) {
            $raw = $this->data['raw'];
        }
        foreach ($raw as $data) {
            $num = count($data);
            $temp = [];
            for ($c=0; $c < $num; $c++) {
                $temp['val'.$c] = $data[$c];
            }
            $rez[] = $temp;
        }
        $r = ['data'=>$rez, 'legend'=>[0,1,5,6,7,8], 'raw' => $raw];
        $this->data = $r;
        return $this;
    }

//    public function parseData()
//    {
//        $md5_sm=substr($this->file_hash, 0,2);
//        $folder = config('filesystems.file_folder');
//        $path = $folder . '/' . $md5_sm . '/' . $this->file_hash;
//        if (($handle = fopen($path, "r")) !== FALSE) {
//            $rez = [];
//            $itog = [];
//            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
//                $num = count($data);
//                $temp = [];
//                for ($c=0; $c < $num; $c++) {
//                    $temp['val'.$c] = iconv("WINDOWS-1251", "UTF-8", $data[$c]);
//                }
//                if (mb_substr($data[0], 0, 1) == '=') {
//                    $itog = $temp;
//                } else {
//                    $rez[] =$temp;
//                }
//            }
////            $r = ['data'=>$rez, 'itog'=>$itog, 'legend'=>array_keys($this->columnLabel)];
//            $r = ['data'=>$rez, 'itog'=>$itog, 'legend'=>[0,1,5,6,7,8]];
//            fclose($handle);
//            $this->data = $r;
//            return $this;
//        }
//    }


    public function parseType($col = 7)
    {
        $d = $this->data;
        $datas= $d['data'];
        $type_1 = ['энер', 'свет',  'эл', 'эн.' ,'ээ','квт', 'счетчик', 'показан'];
        $type_2 = ['взнос', 'членск', 'целев', 'мусор' , 'земля', 'налог', 'ежегодный', 'чл.вз.', 'отходы
        '];
        $ok = [];
        $error = [];
        foreach ($datas as $data) {
            $type = false;
            $str = mb_strtolower($data['val'.$col]);
            foreach ($type_2 as $item) {
                if (stristr($str, $item) && !$type) {
                    $type = 2;
                }
            }
            foreach ($type_1 as $item) {
                if (stristr($str, $item) && !$type) {
                    $type = 1;
                }
            }

            if ($type) {
                $data['type'] = $type;
                $ok[] = $data;
            } else {
                $data['error'] = true;
                $error[] = $data;
            }
        }
        $d['data'] = array_merge($error, $ok);
        $this->data  = $d;
        return $this;
    }

    public function parseStead($col = 5)
    {
        $d = $this->data;

        $datas = $d['data'];
        $ok = [];
        $error = [];
        foreach ($datas as $data) {
            $str = mb_strtolower($data['val'.$col]);
//            echo $str;
//            $stead = Stead::query()->where('number', $str)->first();
            $stead = false;
            if (!$stead){
                $str = str_replace('-', '/', $str);
                $str = str_replace('-', '/', $str);
                $str = str_replace(',', '/', $str);
                $str = str_replace('участок', '', $str);
                $str = str_replace('№', '', $str);
                $str = str_replace(' ', '', $str);
//                $stead = Stead::query()->where('number', $str)->first();
                if(!$stead) {
                   $stead = Stead::query()->where('number', 'like', "%{$str}%")->first();
                }
                if(!$stead) {
                    $str = str_replace('Л сч 502 10линия', '502', $data['val'.$col]);
                    $str = str_replace('Л сч502 10линия', '502', $str);
                    $str = str_replace('288, 289', '288', $str);
                    $str = str_replace('526/525', '525/526', $str);
                    $stead = Stead::query()->where('number', 'like', "%{$str}%")->first();
                }
            }
            if ($stead) {
                $data['stead'] = ['id' => $stead->id, 'number' => $stead->number];
                if ($stead->number == mb_strtolower($data['val'.$col])) {
                    $ok[] = $data;
                } else {
                    $data['error'] = true;
                    $error[] = $data;
                }
            } else {
                $data['stead'] = ['id' => '', 'number' => '-'];
                $data['error'] = true;
                $error[] = $data;
            }

        }
        $d['data'] = array_merge($error, $ok);
        $this->data  = $d;
        return $this;
    }

    public function findDublicate()
    {
        $d = $this->data;
        $datas= &$d['data'];
        foreach ($datas as &$item) {
            $temp_date = explode('-', $item['val0']);
            $temp_time = explode('-', $item['val1']);
            $payment_data = $temp_date[2] . '-' . $temp_date[1] . '-' . $temp_date[0] . ' ' . $temp_time[0] . ':' . $temp_time[1] . ':' . $temp_time[2];
            $find = BillingPayment::query()
                ->where('payment_date', $payment_data)
                ->where('price', (float)str_replace(',', '.',$item['val8']))
                ->where('transaction', $item['val4'])
                ->first();
//            if ($find){
                $item['dubl'] = $find;
//            }
        }
        $this->data  = $d;
        return $this;
    }

    public static function checkFile($file)
    {
        if (($handle = fopen($file, "r")) !== FALSE) {
            $rez = [];
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                $rez[] = count($data);
            }
            return $rez;
            if (count($rez) *12 - 6 == array_sum($rez)) {
                return true;
            }
        }
        return false;
    }
}
