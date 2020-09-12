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


    public function parseData()
    {
        $md5_sm=substr($this->file_hash, 0,2);
        $folder = config('filesystems.file_folder');
        $path = $folder . '/' . $md5_sm . '/' . $this->file_hash;
        if (($handle = fopen($path, "r")) !== FALSE) {
            $rez = [];
            $itog = [];
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
                $temp = [];
                for ($c=0; $c < $num; $c++) {
                    $temp['val'.$c] = iconv("WINDOWS-1251", "UTF-8", $data[$c]);
                }
                if (mb_substr($data[0], 0, 1) == '=') {
                    $itog = $temp;
                } else {
                    $rez[] =$temp;
                }
            }
//            $r = ['data'=>$rez, 'itog'=>$itog, 'legend'=>array_keys($this->columnLabel)];
            $r = ['data'=>$rez, 'itog'=>$itog, 'legend'=>[0,1,5,6,7,8]];
            fclose($handle);
            $this->data = $r;
            return $this;
        }
    }


    public function parseType($col = 7)
    {
        $d = $this->data;
        $datas= &$d['data'];
        foreach ($datas as &$data) {
            $type = false;
            $str = mb_strtolower($data['val'.$col]);
            if (stristr($str, 'взнос')) {
                $type = 1;
            }

            if (stristr($str, 'эл') && !$type) {
                $type = 2;
            }
            if ($type) {
                $data['type'] = $type;
            }
        }
//        $this->find  = $datas;
        $this->data  = $d;
        return $this;
    }

    public function parseStead($col = 5)
    {
        $d = $this->data;
        $datas= &$d['data'];
        foreach ($datas as &$data) {
            $stead = false;
            $str = mb_strtolower($data['val'.$col]);
            $stead = Stead::query()->where('number', $str)->first();
            if (!$stead){
                $str = str_replace('-', '/', $str);
                $stead = Stead::query()->where('number', $str)->first();
                if(!$stead) {
                   $stead = Stead::query()->where('number', 'like', "%{$str}%")->first();
                }
            }
//            if ($type) {
                if ($stead) {
                    $data['stead'] = ['id' => $stead->id, 'number' => $stead->number];
                }
//            }
        }
//        $this->find  = $datas;
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
                ->where('price', (float)$item['val8'])
                ->where('transaction', $item['val4'])
                ->first();
            if ($find){
                $item['dubl'] = $find;
            }
        }
        $this->data  = $d;
        return $this;
    }

    public static function checkFile($file)
    {
        if (($handle = fopen($file, "r")) !== FALSE) {
            $rez = [];
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $rez[] = count($data);
            }
            if (count($rez) *12 - 6 == array_sum($rez)) {
                return true;
            }
        }
        return false;
    }
}
