<?php

namespace Maxa\Ondrej\Discord;

use Discord\OAuth\Discord;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;


/**
 * Service provider.
 */
class Provider extends Discord
{
    /**
     * Gets url for revoking access token.
     *
     * @return string
     */
    public function getRevokeTokenUrl(): string
    {
        return $this->getBaseAccessTokenUrl([]) . '/revoke';
    }

    /**
     * Revokes token
     *
     * @param AccessToken $token
     * @return ResponseInterface
     */
    public function revokeToken(AccessToken $token): ResponseInterface
    {
        $request = $this->getRequest('post', $this->getRevokeTokenUrl(), [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'token' => $token->getToken(),
            ]
        ]);
        return $this->getResponse($request);
    }
}