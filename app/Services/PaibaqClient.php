<?php
namespace App\Services;

use App\Helpers\AutogenHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
class PaibaqClient {

	protected $responseJson;
	protected $responseClient;
	protected $httpCode;
	protected $formData = null;
	protected $requestBody = null;
	protected $query = null;
    protected $additionalHeader;
	private $correlationId;
	public function __construct($correlationId = null){
		if($correlationId) {
			$this->correlationId = $correlationId;
		} else {
			$this->correlationId = AutogenHelper::randomNumber(20);
		}
	}

	public function setFormData($data){
		$this->formData = $data;
		return $this;
	}
    public function setBody($data){
		$this->requestBody = $data;
		return $this;
	}

    public function addHeader($options)
    {
        $this->additionalHeader = $options;
        return $this;
    }

	public function setQuery($query){
		$this->query = $query;
		return $this;
	}

	public function makeRequest($verb, $uri)
	{
		
		Log::info($this->correlationId ." Preparing for connecting paibaq");
		$client = new Client([ 'base_uri' => $_ENV["BACK_OS_VA_NOTIFICATION_URL"], 'timeout'  => 30.0 ]);

		try {
			$options = [
		        "headers" => [
		        	'Accept' => "application/json",
					'Content-Type' => 'application/json',
		        ],
		        // "auth" => [ $_ENV['PAIDBAQ_XENDIT_AREA_KEY'],  $_ENV["PAIDBAQ_XENDIT_AREA_SECRET"] ],
                // 'debug' => true
		       
		    ];
			$logData = [];
		    if($this->formData) {
				
				
				$options[ "headers"]["Content-Type"] = "application/x-www-form-urlencoded";
		    	$options[ "form_params"] = $this->formData;
				$logData = $this->formData;

		    }
            
            if($this->requestBody) {

		    	$options[ "headers"]["Content-Type"] = "application/json";
		    	$options[ "json"] = $this->requestBody;
				$logData = $this->requestBody;

		    }
		    if($this->query){
		    	$options[ "query"] = $this->query;	
				$logData = $this->query;
		    }
		   
            if($this->additionalHeader) {
                $options["headers"] = array_merge($options["headers"], $this->additionalHeader);
            }
			Log::info($this->correlationId ." Connecting paidbaq service with data => ".json_encode($logData));
		    $response = $client->request($verb, $uri , $options);
		    $responseBody = json_decode($response->getBody(), true);
		    
		    $this->setResponseJson(json_decode($response->getBody(), true));
		    $this->setResponseClient($responseBody);
		    $this->setHttpCode(200);
			
			Log::info($this->correlationId ." Response success from paidbaq service with data => ".json_encode($logData));
					    
		} catch (RequestException $e ) {

		    $errorResponse = $e->getResponse();
		    $httpCode = $errorResponse->getStatusCode();
		    $httpReason = $errorResponse->getReasonPhrase();
		    $message = $errorResponse->getBody()->getContents();
		    
		    $message = json_decode($message);
		    $this->setHttpCode($httpCode);
		    $this->setResponseClient([
		    	"error_code" => $message->error_code ?? null,
		    	"error_message" => $message->error_message ?? "Kesalahan server",
		    ]);
			Log::error($this->correlationId ." Response from paidbaq service http code ".$httpCode." - reason ".$httpReason. " with data => ".json_encode($message));
		    
		}

		
		return $this;

	}
	public function setHttpCode($code) {
		$this->httpCode = $code;
		return $this;
	}

	public function setResponseClient($data) {
		$this->responseClient = $data;
		return $this;
	}

	public function getResponseClient() {
		return $this->responseClient;
	}

	public function setResponseJson($responseJson)
	{
		$this->responseJson = $responseJson;
	}

	public function getResponseJson()
	{
		return $this->responseJson;
	}
	
	
	public function __get($prop) {
		return $this->{$prop};
	}
}