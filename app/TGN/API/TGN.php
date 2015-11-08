<?php

namespace TGN\API;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Token\AccessToken;

use TGN\API\Accounts\Account;

class TGN extends AbstractProvider{

	use BearerAuthorizationTrait;

	const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'id';

	protected $version;
	protected $app;

	public function getBaseAuthorizationUrl(){
		return $this->app->config->get('app.core') . '/api/' . $this->version . '/authorize';
	}

	public function getBaseAccessTokenUrl(array $params){
		return $this->app->config->get('app.core') . '/api/' . $this->version . '/access_token';
	}

	public function getResourceOwnerDetailsUrl(AccessToken $token){
		return $this->app->config->get('app.core') . '/api/' . $this->version . '/accounts/me';
	}

	protected function getDefaultScopes(){
		return [
			'account.profile',
			'account.name',
			'account.emails',
			'account.dob'
		];
	}

	protected function getScopeSeparator()
    {
        return ' ';
    }

	protected function checkResponse(ResponseInterface $response, $data){
		if (!empty($data['error'])) {
            $code  = $data['error'];
            $error = $data['message'];
            throw new IdentityProviderException($error, $code, $data);
        }
	}

	protected function createResourceOwner(array $response, AccessToken $token){
		return new Account($response);
	}
}