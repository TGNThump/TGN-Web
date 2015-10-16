<?php

$app->get("/", function() use ($app){
	die("Home");
})->name('home');