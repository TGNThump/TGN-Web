<?php

use Slim\Slim;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

use Noodlehaus\Config;

use TGN\Accounts\Account;

session_cache_limiter(false);
session_start();

define('INC_ROOT', dirname(__DIR__));

require INC_ROOT . '/vendor/autoload.php';

$mode = "production";
if (file_exists(INC_ROOT . '/mode.php')){
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

$app->container->set('account', function(){
	return new Account();
});

$view = $app->view();

$view->appendData(['config' => $app->config]);

$view->parserOptions = [
	'debug' => $app->config->get('twig.debug')
];

$view->parserExtensions = [
	new TwigExtension
];