<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\PaibaqClient;
use Illuminate\Support\Facades\Log;
class DisbursementNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $body;
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try{
            $paibaqClient = (new PaibaqClient())
                ->addHeader(["X-XEN-NOTIFICATION-KEY" => env("BACK_OS_XEN_NOTIFICATION_KEY")])
                ->setBody($this->body)
                ->makeRequest("POST", env("BACK_OS_DISBURSEMENT_NOTIFICATION_URL"));
            Log::info(["abcdefgh",$paibaqClient->getResponseClient()]);
        } catch(\Exception $e){
            Log::info($e->getMessage());
        }
    }
}
