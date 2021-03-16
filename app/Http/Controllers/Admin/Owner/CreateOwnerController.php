<?php

namespace App\Http\Controllers\Admin\Owner;

use App\Http\Controllers\Admin\Report\PdfController;
use App\Http\Controllers\Admin\Report\PrimaryPdfController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Owner\AdminOwnerResource;
use App\Models\Billing\BillingInvoice;
use App\Models\Owner\OwnerUserModel;
use App\Models\Receipt\ReceiptType;
use Illuminate\Http\Request;
use App\Models\Stead;
use App\Models\Receipt\InstrumentReadings;
use App\Models\Receipt\MeteringDevice;
use App\Models\Gardening;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;


class CreateOwnerController
{
    protected $owner;
    protected $data;
    public $error = false;
    public $error_message = '';

    /**
     * проверка на суперадмин или на доступ а админ панель
     */
    public function __construct($data = [])
    {
        if (count($data) > 0) {
            DB::beginTransaction();
            $this->data = $data;
            $this->owner = new OwnerUserModel();
            $this->owner->uid = Uuid::uuid4()->toString();
            if ($this->owner->logAndSave('Добавили собственника')) {
                $this->addValues();
                if (!$this->error) {
                   DB::commit();
                }
            }
            DB::rollBack();
        }
    }

    public function getOwnerId()
    {
        return $this->owner->id;
    }

    public function addValues()
    {
        foreach (array_keys($this->owner->fields) as $field) {
            if (isset($this->data[$field])) {
                if (!$this->owner->setValue($field, $this->data[$field])) {
                    $this->error = true;
                    $this->error_message = 'Ошибка добавления поля';
                }
            }
        }

    }

}
