<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\LogEntry;
use Illuminate\Support\Facades\Log;

class RequestLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->start = microtime(true);
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $request->end = microtime(true);
        $logEntry = new LogEntry();
        $logEntry->duration = $request->end - $request->start;
        $logEntry->url = $request->fullUrl();
        $logEntry->request_method = $request->method();
        $logEntry->request_body = json_decode($request->getContent()) ?? "{}";
        $logEntry->request_header = json_encode($request->header()) ?? "{}";
        $logEntry->ip = $request->ip();
        $logEntry->status_code = $response->getStatusCode();
        $logEntry->response_body = json_decode($response->getContent()) ?? "{}";
        Log::info($response->getContent());
        // $logEntry->response_body =  "Test";
        $logEntry->save();
        // dd($logEntry);

    }
}
