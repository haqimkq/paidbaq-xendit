<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Closure;

class VerifyXenditCallback
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('x-callback-token');

        if ($token !== $_ENV["XENDIT_CALLBACK_TOKEN"]) {
            return $this->http401("You have attempeted to send a request for which you are not authorized.");
        }
        return $next($request);
    }
}
