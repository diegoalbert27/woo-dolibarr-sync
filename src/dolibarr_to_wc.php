<?php

require '../vendor/autoload.php';

// if ( ! defined( 'ABSPATH' ) ) {
//     exit( 'restricted access' );
// }

class DoliberrToWc 
{
    private $api_base_uri = 'http://localhost/dolibarr-14.0.5/htdocs/api/index.php';
    
    private $subscription_key;
	private $username;
	private $password;
	private $token;
	private $client;

	public function __construct(String $username, String $password) {
		$this->username = $username;
		$this->password = $password;

		$this->client = new GuzzleHttp\Client();

        $this->getAccessToken();
	}

    public function getHeaders($withToken = true) {

		$header = [
	        'Accept' => 'application/json',
		];
		
		if ($withToken) {
			if (!isset($this->token->access_token)) $this->getAccessToken();
			$authorization = "Bearer {$this->token->access_token}";
			$header['authorization'] = $authorization;
		}

		

		return $header;
	}

    private function getAccessToken() {
		$response = $this->post("/login", [
			'form_params' => [
		        'login' => $this->username, 
		        'password' => $this->password
		    ],
		    'withToken' => false,
		]);

		$this->token = $response;

		return $response->success->token;
	}

    public function get($endpoint, $params) {
		return $this->request('GET', $this->api_base_uri . $endpoint, $params);
	}

    public function post($endpoint, $params) {
		return $this->request('POST', $this->api_base_uri . $endpoint, $params);
	}

    public function request($type, $endpoint, $params) {

		if (!isset($params['headers'])) {
			$withToken = isset($params['withToken']) && $params['withToken'] === false ? false : true;


			if ($withToken && (!isset($this->token->success->token) || !$this->token->success->token)) $this->getAccessToken();

			$params['headers'] = $this->getHeaders( $withToken );
			unset($params['withToken']);
		}

		try {
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
        $token = $this->token->success->token;

		if (isset($params['id']) && !empty((int)$params['id'])) {
			$ep = "/products/{$params['id']}?DOLAPIKEY=$token";

			return $this->get($ep, ["withToken"=>false]);
		} 

        $ep = "/products?DOLAPIKEY=$token";

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

    public function getCategories(array $params = [])
	{
        $token = $this->token->success->token;

		if (isset($params['id']) && !empty((int)$params['id'])) {
			$ep = "/categories/{$params['id']}?DOLAPIKEY=$token";

			return $this->get($ep, ["withToken"=>false]);
		} 

        $ep = "/categories?DOLAPIKEY=$token";

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

$DoliberrToWc = new DoliberrToWc('webservices', 'PPovCAG2uiaS');

$Products = $DoliberrToWc->getProducts(['id' => 2]);
echo json_encode($Products);
