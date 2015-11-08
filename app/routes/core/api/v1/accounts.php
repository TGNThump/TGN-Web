<?php

use ColorThief\ColorThief;
use \Colors\RandomColor;
use \TGN\Accounts\Account;

use \TGN\API\Response\ResourceCollectionResponse;
use \TGN\API\Response\ResourceResponse;

$app->group('/accounts', function () use ($app) {

	$app->get("/", $app->filters['validToken'](), function() use ($app){

		$accessToken = $app->resourceServer->getAccessToken();
		$session = $accessToken->getSession();
		$data = $app->accounts;

		$response = new ResourceCollectionResponse(
			$app->urlFor('api.v1.accounts.list'),
			$data,
			array(
				'scopes' => $accessToken->getScopes(),
			)
		);

		echo $response->encode();
	})->name('api.v1.accounts.list');;

	$app->get("/:identifier", $app->filters['validToken'](), function($identifier) use ($app){

		$accessToken = $app->resourceServer->getAccessToken();
		$session = $accessToken->getSession();

		if ($identifier == "me"){
			if ($session->getOwnerType() == 'user'){
				$identifier = $session->getOwnerId();
			} else {
				throw new Exception\AccessDeniedException;
				return;
			}
		}

		$account = $app->accounts->where('id', $identifier)->orWhere('terratag', $identifier)->first();
		$isOwner = ($account->id == $session->getOwnerId());

		$response = new ResourceResponse(
			$app->urlFor('api.v1.accounts.get', array( 'identifier' => $identifier )),
			$account,
			array(
				'isOwner' => $isOwner,
				'scopes' => $accessToken->getScopes(),
			)
		);

		echo $response->encode();
	})->name('api.v1.accounts.get');

});