<?php

namespace App\Jobs;

use App\Services\PaibaqClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VirtualAccountNotificationJob implements ShouldQueue
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
                        ->makeRequest("POST", env("BACK_OS_VA_NOTIFICATION_URL"));
        } catch(\GuzzleHttp\Exception\RequestException $e) {
            $errorResponse = $e->getResponse();
		    $httpCode = $errorResponse->getStatusCode();
		    Log::error($_ENV["BRICK_API_URL"]. $_ENV["X-XEN-NOTIFICATION-KEY"] . "  " . $_ENV["BACK_OS_XEN_NOTIFICATION_KEY"] ."  ".$httpCode);
		    $httpReason = $errorResponse->getReasonPhrase();
		    $message = $errorResponse->getBody()->getContents();
			Log::error(" | Response from paidbaq http code : ".$httpCode." | reason : ".$httpReason ."  service with data => ".$message);
		    
        }
    }
}
