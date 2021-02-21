<?php

namespace App\Console\Commands\Invoice;

use App\Http\Controllers\Admin\Bookkeeping\Billing\Invoice\MatchPaymentsAndInvoiceController;
use App\Models\Billing\BillingInvoice;
use App\Models\Billing\BillingPayment;
use App\Models\Stead;
use App\Models\Voting\AnswerModel;
use App\Models\Voting\UserAnswerModel;
use App\Models\Voting\VotingModel;
use Illuminate\Console\Command;

class ActionMatchPaymentsAndInvoice extends Command
{
    /**
     * Имя и подпись консольной команды.
     *
     * @var string
     */
    protected $signature = 'command:invoce/match-payments-and-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сопоставить платежи и счета';

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
        echo 'Сопоставить платежи и счета'. PHP_EOL;
        MatchPaymentsAndInvoiceController::FindAndMatch();
    }
}
