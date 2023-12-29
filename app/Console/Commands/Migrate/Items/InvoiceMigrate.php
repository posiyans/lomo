<?php

namespace App\Console\Commands\Migrate\Items;

use App\Models\MyModel;
use Illuminate\Support\Facades\DB;

/**
 * конвертация счетов и платежей
 *
 */
class InvoiceMigrate
{

    public static function run()
    {
        echo 'конвертация счетов и платежей' . PHP_EOL;
        $invoice_groups = DB::connection('mysql_old')->table('billing_reestrs')->get();
        foreach ($invoice_groups as $item) {
            $fields = ['id', 'title', 'options', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
            $newItem = new BillingInvoiceGroupModel();
            foreach ($fields as $key) {
                $newItem->$key = $item->$key;
            }
            $newItem->rate_group_id = $item->type;
            $newItem->save();
        }

        $invoices = DB::connection('mysql_old')->table('billing_invoices')->get();
        foreach ($invoices as $item) {
            $fields = ['id', 'title', 'stead_id', 'price', 'user_id', 'created_at', 'updated_at', 'deleted_at'];
            $newItem = new BillingInvoiceModel();
            foreach ($fields as $key) {
                $newItem->$key = $item->$key;
            }
            $newItem->rate_group_id = $item->type;
            $newItem->invoice_group_id = $item->reestr_id;
            $newItem->is_paid = $item->paid;
            $newItem->description = ['description' => $item->description];
            $newItem->save();
        }

        $payments = DB::connection('mysql_old')->table('billing_payments')->get();
        foreach ($payments as $item) {
            $fields = ['id', 'stead_id', 'description', 'price', 'payment_date', 'user_id', 'payment_type', 'raw_data', 'invoice_id', 'error', 'created_at', 'updated_at', 'deleted_at'];
            $newItem = new BillingPaymentModel();
            foreach ($fields as $key) {
                $newItem->$key = $item->$key;
            }
            $newItem->rate_group_id = $item->type;
            $newItem->save();
        }
    }
}

class BillingInvoiceGroupModel extends MyModel
{
    public $timestamps = false;
}

class BillingInvoiceModel extends MyModel
{

    protected $casts = [
        'paid' => 'boolean',
        'price' => 'decimal:2',
        'description' => 'array',
    ];
    public $timestamps = false;
}

class BillingPaymentModel extends MyModel
{
    public $timestamps = false;

    protected $casts = [
        'error' => 'boolean',
        'price' => 'decimal:2',
    ];
}