<?php

namespace TGN\API\Accounts;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class Account implements ResourceOwnerInterface{

	protected $response;

	public function __construct(array $response){
		$this->response = $response;
	}

	public function getId(){
		return $this->response['id'];
	}

	public function getTerraTag(){
		return $this->reponse['terratag'];
	}

	public function getFirstName(){
		return $this->response['name']['firstName'];
	}

	public function getLastName(){
		return $this->response['name']['lastName'];
	}

	public function getEmail(){
		return $this->response['emails'][0]['value'];
	}

	public function toArray(){
		return $this->response;
	}
}