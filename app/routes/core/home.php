<?php

$app->get("/", function() use ($app){
	if ($app->mode != 'development')
		return $app->response->redirect($app->config->get('app.www'));

	echo "Development Mode";
});