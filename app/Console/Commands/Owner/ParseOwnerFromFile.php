<?php

namespace App\Console\Commands\Owner;

use App\Models\Owner\OwnerUserModel;
use App\Models\Owner\OwnerUserSteadModel;
use App\Models\Stead;
use App\Models\Voting\AnswerModel;
use App\Models\Voting\UserAnswerModel;
use App\Models\Voting\VotingModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ParseOwnerFromFile extends Command
{
    /**
     * Имя и подпись консольной команды.
     *
     * @var string
     */
    protected $signature = 'command:owner/parse-owner-from-file {file=false : фаил с собствениками}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Заполнить  собственников из файла';

    protected $owner = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileName = false;
        if ($this->hasArgument('file')) {
            $fileName = $this->argument('file');
        }
        if ($fileName) {
            $path = __DIR__ . '/../../../../' . $fileName;
            echo $path;
            echo PHP_EOL;
            if (file_exists($path)) {
                echo 'ok';
                echo PHP_EOL;
                $this->readCsv($path);
            }
        } else {
            echo 'Укажите расположение файла' . PHP_EOL;
            echo 'Участок;размер;"ФИО";"Адресс";моб телефон;доп. телефоны' . PHP_EOL;
        }

        echo PHP_EOL;
    }

    public function readCsv($file)
    {
        $row = 1;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
                if ($num = 6) {
                    if ($stead = $this->checkStead($data[0], $data[1])) {
//                        echo $stead;
                        $fio = $this->parseFio($data[2]);
                        $fio_str = implode(' ', $fio);
                        $owner = $this->checkDublicateOwner($fio_str);
                        if ($owner) {
                            echo 'Уже есть!!';
                            echo PHP_EOL;
                        } else {
                            echo $stead->number;
                            echo PHP_EOL;
                            $phone = $this->parsePhone($data[4]);
                            $owner = $this->addOwner($fio, $data[3], $phone, $data[5]);
                        }
                        if ($owner) {
                            $this->addStead($owner, $stead);
                        }
                    }
                }
            }

            fclose($handle);
        }
    }

    /**
     * парсер телефона
     *
     * @param $str
     * @return string
     */
    public function parsePhone($str)
    {
        $ph = str_replace(' ', '', trim($str));
        $ph = preg_replace('/[^0-9]/', '', $ph);
        if (substr($ph, 0, 1) == 8) {
            $ph = '+7' . substr($ph, 1);
        }
        if (substr($ph, 0, 1) == 7) {
            $ph = '+' . $ph;
        }
        if (substr($ph, 0, 1) == 9) {
            $ph = '+7' . $ph;
        }
        if (strlen($ph) == 12) {
//            echo $ph;
//            echo PHP_EOL;
            return  substr($ph, 0, 2). '('. substr($ph, 2,  3) . ')' . substr($ph, 5, 3) . '-'. substr($ph, 8, 2) . '-'. substr($ph, 10, 2);
        }
        return '';
    }

    /**
     * парсер ФИО
     *
     * @param $str
     * @return array
     */
    public function parseFio($str)
    {
        $str = str_replace('  ', ' ', trim($str));
        $str = str_replace('  ', ' ', $str);
        $f = explode(' ', $str);
        $data = [];
        for ($i = 0; $i < 4; $i++) {
            if (isset($f[$i])) {
                $data[$i] = $f[$i];
            } else {
                $data[$i] = '';
            }
        }
        if (count($f) > 3) {
            for ($i =3; $i < count($f); $i++) {
                $data[2] .= ' '.$f[$i];
            }
        }

        return $data;
    }

    /**
     * находим участок
     *
     * @param $num
     * @param $size
     * @return false
     */
    public function checkStead($num, $size)
    {
        $stead = Stead::where('number', $num)->where('size', $size)->first();
        if ($stead) {
            return $stead;
        }
        return false;
    }


    /**
     * проверить на повтор собственника
     *
     * @param $fio
     * @return OwnerUserModel|false|mixed
     */
    public function checkDublicateOwner($fio)
    {
        $owners = OwnerUserModel::all();
        $cache = [];
        foreach ($owners as $owner) {
            $cache[$owner->fullName()] = $owner;
        }
        if (isset($cache[trim($fio)])) {
            return $cache[trim($fio)];
        }
        return false;
    }

    /**
     * добавить собственника
     *
     * @param $fio
     * @param $adr
     * @param $phone
     * @param $phone_add
     * @return OwnerUserModel|false
     */
    public function addOwner($fio, $adr, $phone, $phone_add)
    {
        $owner = new OwnerUserModel();
        $owner->uid = Uuid::uuid4()->toString();
        DB::beginTransaction();
        $error = false;
        if ($owner->logAndSave('Добавили собственника')) {
            if (!$owner->setValue('surname', $fio[0])) {
                $error= true;
            }
            if (!$owner->setValue('name', $fio[1])) {
                $error= true;
            }
            if (!$owner->setValue('middle_name', $fio[2])) {
                $error= true;
            }
            if (!$owner->setValue('address', $adr)) {
                $error= true;
            }
            if (!$owner->setValue('address_notifications', $adr)) {
                $error= true;
            }
            if (!$owner->setValue('general_phone', $phone)) {
                $error= true;
            }
            if (!$owner->setValue('phones', $phone_add)) {
                $error= true;
            }
            if (!$error) {
                DB::commit();
                return $owner;
            }
        }
        DB::rollBack();
        return false;
    }


    /**
     * добавить участок собственнику
     *
     * @param OwnerUserModel $owner
     * @param Stead $stead
     * @return bool
     */
    public function addStead(OwnerUserModel $owner, Stead $stead)
    {
        if (!(OwnerUserSteadModel::where('stead_id', $stead->id)->first())) {
            $userStead = new  OwnerUserSteadModel();
            $userStead->owner_uid = $owner->uid;
            $userStead->stead_id = $stead->id;
            $userStead->proportion = 100;
            if ($userStead->logAndSave('Добавлен участок собственнику')) {
                return true;
            }
        }
        return false;
    }
}
