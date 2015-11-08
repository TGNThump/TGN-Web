<?php

namespace TGN\API\Response;

use Slim\Slim;

class Response{

	protected $app;

	protected $uri;
	protected $data;
	protected $links;

	public function __construct($uri = null, $data = array()){
		$this->app = Slim::getInstance();
		$this->uri = $uri;

		if (!is_array($data) && !$data instanceof \Traversable){
			throw new \RuntimeException('The $data parameter must be an array or an object implementing the Traversable interface.');
		}

		$this->data = $data;
		$this->links = array();
		$this->addLink("self", $uri);
	}

	public static function decode($json){

	}

	public function encode(){
		$meta = array();
		$meta['_links'] = array();

		foreach($this->links as $rel => $href){
			$meta['_links'][$rel] = array(
				'href' => $href,
			);
		}

		return json_encode($meta + $this->data);
	}

	public function addLink($rel, $href){
		$this->links[$rel] = $href;
	}

	public function setData(array $data){
		$this->data = $data;
	}

}