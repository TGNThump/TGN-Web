<?php

namespace TGN\API;

use Illuminate\Database\Eloquent\Model;
use Slim\Slim;

abstract class Resource extends Model{

	protected $app;

	public function __construct(){
		$this->app = Slim::getInstance();
	}

	abstract public function encode($options = array());

	protected function getOption($options, $key, $default){
		return (array_key_exists($key, $options) ? $options[$key] : $default);
	}

	protected function isOwner(){
		return $this->id == $this->app->resourceServer->getAccessToken()->getSession()->getOwnerId();
	}

	protected function hasScope($scopes, $key){
		return array_key_exists($key, $scopes);
	}

}