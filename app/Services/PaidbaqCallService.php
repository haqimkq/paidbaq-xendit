<?php


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use App\Models\ApiClient;

class PaidbaqCallService {

    protected $responseJson;
    protected $responseClient;
    protected $httpCode;
    protected $formData = null;
    protected $query = null;

    public function setFormData($data)
    {
        $this->formData = $data;
        return $this->formData;
    }

    public function setQuery($query)
    {
        $this->query = $query;
        return $this->query;
    }

    public function makeRequest($verb, $uri)
    {
        $client = new Client(['base_uri' => $_ENV["PBAQ_API_URL"], 'timeout' => 30.0]);

        $apiClient = ApiClient("user", function($query)
        {
            $query->where("id", auth()->user()->id);
        })->first();
    }
}
