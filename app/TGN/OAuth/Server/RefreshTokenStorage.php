<?php

namespace TGN\OAuth\Server;

use Illuminate\Database\Capsule\Manager as Capsule;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\RefreshTokenInterface;

class RefreshTokenStorage extends AbstractStorage implements RefreshTokenInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($token)
    {
        $result = Capsule::table('oauth_refresh_tokens')
                            ->where('refresh_token', $token)
                            ->get();

        if (count($result) === 1) {
            $token = (new RefreshTokenEntity($this->server))
                        ->setId($result[0]->refresh_token)
                        ->setExpireTime($result[0]->expires_at)
                        ->setAccessTokenId((new \DateTime($result[0]->expires_at))->getTimestamp());

            return $token;
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function create($token, $expireTime, $accessToken)
    {
        Capsule::table('oauth_refresh_tokens')
                    ->insert([
                        'refresh_token'     =>  $token,
                        'access_token'    =>  $accessToken,
                        'expires_at'   =>  date("Y-m-d H:i:s", $expireTime),
                    ]);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(RefreshTokenEntity $token)
    {
        Capsule::table('oauth_refresh_tokens')
                            ->where('refresh_token', $token->getId())
                            ->delete();
    }
}
