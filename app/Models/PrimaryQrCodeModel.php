<?php

namespace App\Models;


class PrimaryQrCodeModel
{
    //
    public $out;

    public function __construct()
    {
        $this->out = 'ST00012';
    }

    public function setDetailsGardient($data)
    {
        $this->out .= isset($data['name']) ? '|Name=' . $data['name']: '';
        $this->out .= isset($data['personal_acc']) ? '|PersonalAcc=' . $data['personal_acc'] : '';
        $this->out .= isset($data['bank_name']) ? '|BankName=' . $data['bank_name'] : '';
        $this->out .= isset($data['bic']) ? '|BIC=' . $data['bic'] : '';
        $this->out .= isset($data['cor_acc']) ? '|CorrespAcc=' . $data['cor_acc'] : '';
        $this->out .= isset($data['inn']) ? '|PayeeINN=' . $data['inn'] : '';
    }

    public function setDetailsUser($data)
    {
        $this->out .= isset($data['last_name'])  ? '|LastName=' . $data['last_name'] : '';
        $this->out .= isset($data['first_name']) ? '|FirstName=' . $data['first_name'] : '';
        $this->out .= isset($data['middle_name']) ? '|MiddleName=' . $data['middle_name']: '';
    }

    /**
     * установить назначение платежа
     *
     * @param $description
     */
    public function setDescription($description){
        if ($description) {
//            $this->out .= '|Purpose=' . mb_substr($description, 0, 115);
            $this->out .= '|Purpose=' . $description;
        }
    }

    /**
     * установить номер участка
     *
     * @param $number
     */
    public function setSteadNumber($number){
        if ($number) {
            $this->out .= '|PersAcc=' . $number;
        }
    }

    /**
     * установить сумму платежа в копейках
     *
     * @param int $cash
     */
    public function setCash(int $cash){
        if ($cash > 0) {
            $this->out .= '|Sum=' . $cash;
        }
    }

    public function getFile($name = false){
        \PHPQRCode\QRcode::png($this->out,
            $outfile = $name,
            $level = 2,
            $size = 3,
            $margin = 4);
    }

}
