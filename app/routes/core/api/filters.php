<?php

$app->filters += array(
	'validToken' => function() use ($app){
		return function() use ($app){
			try{
				$app->resourceServer->isValidRequest(false);
			} catch (\Exception $e){
				$app->response->headers->set('Content-Type', 'application/json');
				if (!isset($e->httpStatusCode)) $e->httpStatusCode = 500;
				$app->halt($e->httpStatusCode, json_encode(array(
					'error' => $e->httpStatusCode,
					'message' => $e->getMessage()
				)));
			}
		};
	},
);