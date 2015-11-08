<?php

require INC_ROOT . '/app/routes/core/api/filters.php';

function versions($app){
	return array(
		'v1' => array(
			'version' => '1.0.0',
			'stream' => 'development',
			//'link' => $app->urlFor('api.v1'),
			'endpoints' => getEndpoints($app, '/api/v1'),
		),
	);
}

$app->group('/api', function () use ($app) {
	$app->response->headers->set('Content-Type', 'application/json');

	require INC_ROOT . '/app/routes/core/api/v1/v1.php';

	$app->get("/", function() use ($app){
		echo json_encode(array(
			'api' => 'Terra Gaming Network API',
			'versions' => versions($app),
		));
	})->name('api');

});

$app->error(function (\Exception $e) use ($app) {
	echo json_encode(array(
		'error' => $app->response->getStatus(),
		'message' => $e->getMessage()
	));
});

$app->notFound(function () use ($app) {
	$app->response->setStatus(404);
    echo json_encode(array(
		'error' => $app->response->getStatus(),
		'message' => 'Endpoint \'' . $app->request->getResourceUri() .  '\' Not Found'
	));
});

function getEndpoints($app, $path){
	$endpoints = array();

	foreach($app->router->getNamedRoutes() as $route){
		$endpoints[$route->getPattern()] = $app->urlFor($route->getName());
	}

	return array_values(array_filter($endpoints, function($value) use ($path){
		return substr($value, 0, strlen($path)) === $path;
	}, ARRAY_FILTER_USE_KEY));
}