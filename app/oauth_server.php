<?php

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\ResourceServer;

// OAuth 2.0 Server
$storage['accessTokenStorage'] = new TGN\OAuth\Server\AccessTokenStorage();
$storage['authCodeStorage'] = new TGN\OAuth\Server\AuthCodeStorage();
$storage['clientStorage'] = new TGN\OAuth\Server\ClientStorage();
$storage['refreshTokenStorage'] = new TGN\OAuth\Server\RefreshTokenStorage();
$storage['scopeStorage'] = new TGN\OAuth\Server\ScopeStorage();
$storage['sessionStorage'] = new TGN\OAuth\Server\SessionStorage();

$resourceServer = new ResourceServer(
	$storage['sessionStorage'],
	$storage['accessTokenStorage'],
	$storage['clientStorage'],
	$storage['scopeStorage']
);

$authServer = new AuthorizationServer();
	$authServer->setSessionStorage($storage['sessionStorage']);
	$authServer->setAccessTokenStorage($storage['accessTokenStorage']);
	$authServer->setRefreshTokenStorage($storage['refreshTokenStorage']);
	$authServer->setClientStorage($storage['clientStorage']);
	$authServer->setScopeStorage($storage['scopeStorage']);
	$authServer->setAuthCodeStorage($storage['authCodeStorage']);

$clientCredentials = new \League\OAuth2\Server\Grant\ClientCredentialsGrant();
$authServer->addGrantType($clientCredentials);

$passwordGrant = new \League\OAuth2\Server\Grant\PasswordGrant();
$passwordGrant->setVerifyCredentialsCallback(function ($username, $password) use ($app) {
    $account = $app->account->where('terratag', $username)->first();
    if (count($account) !== 1) {
        return false;
    }

    if ($app->hash->passwordCheck($password, $account->password)) {
        return $account->id;
    }

    return false;
});
$authServer->addGrantType($passwordGrant);

$authCodeGrant = new \League\OAuth2\Server\Grant\AuthCodeGrant();
$authServer->addGrantType($authCodeGrant);

$refrehTokenGrant = new \League\OAuth2\Server\Grant\RefreshTokenGrant();
$authServer->addGrantType($refrehTokenGrant);

$app->container->singleton('resourceServer', function() use ($resourceServer){
	return $resourceServer;
});

$app->container->singleton('authServer', function() use ($authServer){
	return $authServer;
});