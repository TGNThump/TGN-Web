<?php

namespace TGN\API\Response;

use TGN\API\Resource;
use Illuminate\Database\Query\Builder;

class ResourceCollectionResponse extends PaginatedResponse{

	public function __construct($uri = null, Resource $builder, $options = array()){
		parent::__construct($uri);

		$data = array();

		$models = $builder->skip($this->offset)->take($this->limit)->get();
		foreach ($models as $model){
			$data[] = $model->encode($options);
		}

		$this->setData($data);
	}

}