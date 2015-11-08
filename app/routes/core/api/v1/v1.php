<?php

$app->group('/v1', function () use ($app) {

	$app->get("/", function() use ($app){
		echo json_encode(versions($app)['v1']);
	})->name('api.v1');

	$app->post("/access_token", function() use ($app){
		
		try{
			echo json_encode($app->authServer->issueAccessToken());
		} catch (\Exception $e){
			if (!isset($e->httpStatusCode)) $e->httpStatusCode = 500;
			$app->response->setStatus($e->httpStatusCode);
			echo json_encode(array(
				'error' => $e->httpStatusCode,
				'message' => $e->getMessage(),
			));
			return;
		}

	})->name('api.v1.access_token');

	$app->get("/tokeninfo", $app->filters['validToken'](), function() use ($app){
		$accessToken = $app->resourceServer->getAccessToken();
	    $session = $app->resourceServer->getSessionStorage()->getByAccessToken($accessToken);
	    $token = [
	        'owner_id' => $session->getOwnerId(),
	        'owner_type' => $session->getOwnerType(),
	        'access_token' => $accessToken->getId(),
	        'expires_in' =>  $accessToken->getExpireTime() - time(),
	        'client_id' => $session->getClient()->getId(),
	        'scopes' => $accessToken->getScopes(),
	    ];

	    echo json_encode($token);
	})->name('api.v1.tokeninfo');

	$app->get("/authorize", function() use ($app){
		
		try{
			$authParams = $app->authServer->getGrantType('authorization_code')->checkAuthorizeParams();
		} catch (\Exception $e){
			if (!isset($e->httpStatusCode)) $e->httpStatusCode = 500;
			$app->response->setStatus($e->httpStatusCode);
			echo json_encode(array(
				'error' => $e->httpStatusCode,
				'message' => $e->getMessage(),
			));
			$redirectUri = $app->request->get('redirect_uri');
			if (isset($redirectUri)){
				//$app->response->redirect($redirectUri);
			}
			return;
		}

		// Show the user a sign-in screen and ask them to authorize the requested scopes
		// print_r($authParams);
		// die();

		// Create a new authorize request which will respond with a redirect URI that the user will be redirected to
 		$redirectUri = $app->authServer->getGrantType('authorization_code')->newAuthorizeRequest('user', 1, $authParams);

 		$app->response->redirect($redirectUri);

	})->name('api.v1.authorize');

	require INC_ROOT . '/app/routes/core/api/v1/accounts.php';

});