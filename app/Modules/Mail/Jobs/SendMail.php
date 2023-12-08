<?php

namespace App\Modules\Mail\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle($ddata)
    {
        Log::error($ddata);
        $apiToken = env('TELEGRAM_BOT_TOKEN');
        $data = [
            'chat_id' => env('TELEGRAM_BOT_CHAT_ID'),
            'text' => 'Hello from PHP!'
        ];
        $response = file_get_contents(
            "https://api.telegram.org/bot$apiToken/sendMessage?" .
            http_build_query($data)
        );
    }
}
