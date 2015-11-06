<?php

$app->group('/v1', function () use ($app) {

	$app->get("/", function() use ($app){
		echo json_encode(versions($app)['v1']);
	})->name('api.v1');

	$app->get("/test", function(){
		echo "test";
	})->name('api.v1.test');

});