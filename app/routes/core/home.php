<?php

$app->get("/", function() use ($app){
	if ($app->mode != 'development')
		return $app->response->redirect($app->config->get('app.www'));
	
	$app->response->headers->set('Content-Type', 'application/json');

	echo json_encode(array(
		'api' => $app->urlFor('api'),
		'oauth' => '',
		'common' => ''
	));
});