<?php

namespace App\Http\Middleware;
use App\Models\ApiClient;

use Closure;

class WithBasicAuth
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
        $environment = ($_ENV["APP_ENV"] == "production") ? "production" : "development";
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
        $apiClientModel = ApiClient::where([
            "client_id" => $_SERVER['PHP_AUTH_USER'],
            "client_Secret" => $_SERVER['PHP_AUTH_PW'],
            "status" => "active",
            "environment" => $environment,
        ])->first();
        $is_not_authenticated = (!$has_supplied_credentials || !$apiClientModel);
        if($is_not_authenticated){
            return \response()->json([
                "error_code" => "401",
                "error_message" => "Unauthenticated user"
            ], 401);
        }else if(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW'])){
            return \response()->json([
                "error_code" => "401",
                "error_message" => "Unauthenticated user"
            ], 401);
        }
        return $next($request);
    }
}
