<?php

require dirname(__FILE__).'/../vendor/autoload.php';

// if ( ! defined( 'ABSPATH' ) ) {
//     exit( 'restricted access' );
// }

class doli_api 
{
    private $api_base_uri = '/api/index.php';
    
    private $subscription_key;
	private $url_base;
	private $username;
	private $token;
	private $client;

	public function __construct(String $url_base, String $username, String $subscription_key) {
		$this->url_base = $url_base;
		$this->username = $username;
		$this->subscription_key = $subscription_key;

		$this->client = new GuzzleHttp\Client();

        $this->getAccessToken();
	}

    public function getHeaders($withToken = true) {

		$header = [
	        'Accept' => 'application/json',
	        'DOLAPIKEY' => $this->subscription_key,
		];
		
		if ($withToken) {
			if (!isset($this->token->access_token)) $this->getAccessToken();
			$authorization = "Bearer {$this->token->access_token}";
			$header['authorization'] = $authorization;
		}

		

		return $header;
	}

    private function getAccessToken() {
    	/*
		$response = $this->post("/login", [
			'form_params' => [
		        'login' => $this->username, 
		        'password' => $this->password
		    ],
		    'withToken' => false,
		]);

		$this->token = $response;
		*/
		return $this->subscription_key;
	}

    public function get($endpoint, $params) {
		return $this->request('GET', $this->url_base.$this->api_base_uri . $endpoint, $params);
	}

    public function post($endpoint, $params) {
		return $this->request('POST', $this->url_base.$this->api_base_uri . $endpoint, $params);
	}

    public function request($type, $endpoint, $params) {

		if (!isset($params['headers'])) {
			$withToken = isset($params['withToken']) && $params['withToken'] === false ? false : true;


			if ($withToken && (!isset($this->token->success->token) || !$this->token->success->token)) $this->getAccessToken();

			$params['headers'] = $this->getHeaders( $withToken );
			unset($params['withToken']);
		}

		try {
			$params["DOLAPIKEY"] = $this->subscription_key;
			$response = $this->client->request($type, $endpoint, $params);
			return (int)$response->getStatusCode() === 200 ? json_decode($response->getBody()) : null;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

    public function refreshToken() {
		if ($this->token) return $this->token->refresh_token;
		return $this->getAccessToken();
	}

    public function getProducts(array $params = [])
	{

		if (isset($params['id']) && !empty((int)$params['id'])) {
			$ep = "/products/{$params['id']}";

			return $this->get($ep, ["withToken"=>false]);
		} 

        $ep = "/products";

		// if (isset($params['page']) && !empty((int)$params['page'])) {
		// 	$query['page'] = (int)$params['page'];
		// } else {
		// 	$query['page'] = 1;
		// }

		return $this->get($ep, [
			//'query' => $query,
			"withToken" => false,
			'verify' => false
		]);
	}

    public function getCategories(array $params = [])
	{

		if (isset($params['id']) && !empty((int)$params['id'])) {
			$ep = "/categories/{$params['id']}";

			return $this->get($ep, ["withToken"=>false]);
		} 

        $ep = "/categories";

		// if (isset($params['page']) && !empty((int)$params['page'])) {
		// 	$query['page'] = (int)$params['page'];
		// } else {
		// 	$query['page'] = 1;
		// }

		return $this->get($ep, [
			//'query' => $query,
			"withToken" => false
		]);
	}
}

