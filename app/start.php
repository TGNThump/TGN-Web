<?php

use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

use Noodlehaus\Config;
use RandomLib\Factory as RandomLib;

use TGN\Accounts\Account;

use TGN\Helpers\Hash;
use TGN\Validation\Validator;

session_cache_limiter(false);
session_start();

define('INC_ROOT', dirname(__DIR__));

require INC_ROOT . '/vendor/autoload.php';

$mode = "production";
if (isset($testing)){
	$mode = "template";
} else if (file_exists(INC_ROOT . '/mode.php')){
	$mode = file_get_contents(INC_ROOT . '/mode.php');
}

ini_set('display_errors', $mode == 'development' ? "On" : "Off");

$app = new Slim([
	'mode' => $mode,
	'view' => new Twig(),
	'templates.path' => INC_ROOT . '/app/views'
]);

$app->configureMode($app->mode, function() use ($app){
	$app->config = Config::load(INC_ROOT . '/app/config/' . $app->mode . '.php');
});

require 'database.php';

$app->container->set('accounts', function(){
	return new Account();
});

$filters = array();

$app->container->set('filters', function() use ($filters){
	return $filters;
});

$app->container->singleton('hash', function() use ($app){
	return new Hash($app->config);
});

$app->container->singleton('validation', function() use ($app){
	return new Validator();
});

$app->container->singleton('randomLib', function(){
	$factory = new RandomLib;
	return $factory->getMediumStrengthGenerator();
});

require 'oauth_server.php';
require 'oauth_clients.php';

$view = $app->view();

$view->appendData(['config' => $app->config]);

$view->parserOptions = [
	'debug' => $app->config->get('twig.debug')
];

$view->parserExtensions = [
	new TwigExtension
];