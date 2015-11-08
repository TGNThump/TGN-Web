<?php

use TGN\API\TGN;

use GuzzleHttp\Client as HttpClient;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Google;

$providers = array(
	'tgn' => new TGN([
		'clientId' 	=> $app->config->get('oauth.tgn.clientId'),
		'clientSecret' => $app->config->get('oauth.tgn.clientSecret'),
		'redirectUri' => $app->config->get('app.core') . '/oauth/tgn',
		'version' => $app->config->get('oauth.tgn.version'),
		'app' => $app,
	]),
	'facebook' => new Facebook([
		'clientId' => $app->config->get('oauth.facebook.clientId'),
		'clientSecret' => $app->config->get('oauth.facebook.clientSecret'),
		'redirectUri' => $app->config->get('app.core') . '/oauth/facebook',
		'graphApiVersion' => $app->config->get('oauth.facebook.version'),
		'ssl.certificate_authority' => 'system',
	]),
	'github' => new Github([
		'clientId' => $app->config->get('oauth.github.clientId'),
		'clientSecret' => $app->config->get('oauth.github.clientSecret'),
		'redirectUri' => $app->config->get('app.core') . '/oauth/github'
	]),
	'google' => new Google([
		'clientId' => $app->config->get('oauth.google.clientId'),
		'clientSecret' => $app->config->get('oauth.google.clientSecret'),
		'redirectUri' => 'http://localhost/terragaming.co.uk/core/oauth/google',
		'hostedDomain' => $app->config->get('app.core'),
	]),
);

$app->container->set('api', function() use ($providers){
	return $providers['tgn'];
});

$app->container->set('providers', function() use ($providers){
	return $providers;
});