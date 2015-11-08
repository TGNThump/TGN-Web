<?php

namespace TGN\API\Response;

use TGN\API\Resource;

class ResourceResponse extends Response{

	public function __construct($uri = null, Resource $resource, $options = array()){
		parent::__construct($uri, $resource->encode($options));
	}

}