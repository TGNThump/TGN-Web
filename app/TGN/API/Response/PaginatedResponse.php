<?php

namespace TGN\API\Response;

class PaginatedResponse extends Response{

	protected $offset;
	protected $limit;
	protected $count;

	public function __construct($uri = null, $data = array(), $offset = null, $limit = null){
		parent::__construct($uri, $data);
		
		if ($offset == null){$offset = $this->app->request->params("offset");}
		if ($limit == null){$limit = $this->app->request->params("limit");}
		if ($offset == null){$offset = 0;}
		if ($limit == null){$limit = 25;}

		$offset = intval($offset);
		$limit = intval($limit);

		if (!is_int($offset) || !is_int($limit) || $limit <= 0 || $offset < 0){
			throw new \RuntimeException('Invalid pagination parameters.');
		}

		$this->offset = $offset;
		$this->limit = $limit;

		$this->addLink('next', $this->uri . '?offset=' . ($this->offset + $this->limit) . '&limit=' . $this->limit);
		
		if ($this->offset - $this->limit >= 0){
			$this->addLink('last', $this->uri . '?offset=' . ($this->offset - $this->limit) . '&limit=' . $this->limit);
		}
	}

}