<?php

namespace App\Traits;

trait ApiResponse {

    public function httpSuccess($payload = null, $errroCode = "0200", $httpCode = 200)
    {
        $response["error_code"] = $errroCode;
        $response["error_message"] = "suceess";
        if($payload){
            $response["payload"] = $payload;
        }
        return response()->json($response, $httpCode);
    }

    public function httpInvalidParams($parameterName = null, $errroCode = "0400", $httpCode = 400)
    {

        try{
            $stringError = implode(", ", json_decode($parameterName));
        } catch (\Exception $e) {
            $stringError = "";
        }
        $response["error_code"] = $errroCode;
        $response["error_message"] = "Bad Request. Invalid parameter ".$stringError;
        $response["error_details"] = json_decode($parameterName);
        return response()->json($response, $httpCode);

    }

    public function httpUnprocessableEntity($parameterName = null, $errroCode = "0422", $httpCode = 422)
    {

       
        $response["error_code"] = $errroCode;
        $response["error_message"] = "Bad Request. ".$parameterName;
        return response()->json($response, $httpCode);

    }

    public function httpError($message= null , $errorCode= "0500", $httpCode = 500)
    {
        $response["error_code"] = $errorCode;
        $response["error_message"] = $message;
        return response()->json($response, $httpCode);
    }

    public function http401($message= null , $errorCode= "0401", $httpCode = 401)
    {
        if($message == null ){
            $message = "Unauthorized";
        }
        $response["error_code"] = $errorCode;
        $response["error_message"] = $message;
        return response()->json($response, $httpCode);
    }

    public function httpServiceUnavailable($message = null, $errorCode= "0503", $httpCode = 503){
        return response()->json([
            "error_code" => $errorCode ,
            "error_message" => $message ?? "Service unavailable"
        ], $httpCode);
    }

    public function httpNotFound($message = null, $errorCode= "0404", $httpCode = 404){
        return response()->json([
            "error_code" => $errorCode ,
            "error_message" => $message ?? "Not found"
        ], $httpCode);
    }
    public function httpBadGateway($message = null, $errorCode= "0502", $httpCode = 502){
        return response()->json([
            "error_code" => $errorCode ,
            "error_message" => "Bad Gateway"
        ], $httpCode);
    }


}