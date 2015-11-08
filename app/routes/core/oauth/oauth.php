<?php

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

$app->group('/oauth', function () use ($app) {
	$app->response->headers->set('Content-Type', 'application/json');

	$app->get("(/:provider)", function($provider = "tgn") use ($app){
		if (!array_key_exists($provider, $app->providers)){
			return $app->notFound();
		}

		try{
			$providerName = $provider;
			$provider = $app->providers[$provider];
			$code = $app->request->get('code');
			$error = $app->request->get('error');

			if (isset($error)){
				$code  = $app->request->get('error');
				$error = $app->request->get('message');
				echo json_encode(array(
					'error' => $code,
					'message' => $error,
				));
			} else if (!isset($code)){
				$_SESSION['api_state'] = $provider->getState();
				$options = array();
				$scope = $app->config->get('oauth.' . $providerName . '.scope');
				if (isset($scope)){
					$options['scope'] = $scope;
				}

				$app->response->redirect($provider->getAuthorizationUrl($options));
			} elseif (empty($app->request->get('state') || $app->request->get('state') !== $_SESSION['api_state'])){
				unset($_SESSION['oauth2state']);
				exit('Invalid state');
			} else {
				
				$result = array();
				$accessToken = $provider->getAccessToken('authorization_code', [
					'code' => $code
				]);

				$result['access_token'] = $accessToken->getToken();
				$result['refresh_token'] = $accessToken->getRefreshToken();
				$result['expires'] = $accessToken->getExpires();
				if ($result['expires'] != null)
					$result['expired'] = ($accessToken->hasExpired() ? 'expired' : 'not expired');

				$resourceOwner = $provider->getResourceOwner($accessToken);

				$result['resource_owner'] = $resourceOwner->toArray();

				echo json_encode($result);
			}

		} catch (\Exception $e){
			if (is_a($e, "League\OAuth2\Client\Provider\Exception\IdentityProviderException")){
				$app->response->setStatus($e->getCode());
				echo json_encode(array(
					'error' => $e->getCode(),
					'message' => $e->getMessage(),
				));
				return;
			} else {
				if (!isset($e->httpStatusCode)) $e->httpStatusCode = 500;
				$app->response->setStatus($e->httpStatusCode);
				echo json_encode(array(
					'error' => $e->httpStatusCode,
					'type' => get_class($e),
					'message' => $e->getMessage(),
					'detail' => array(
						'file' => $e->getFile(),
						'line' => $e->getLine(),
					),
					'debug' => $e->getTrace(),
				));
				return;
			}
		}

	})->name('oauth');

	$app->get("/", function() use ($app){
		$app->response->redirect($app->urlFor('oauth'));
	});

});