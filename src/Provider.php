<?php

namespace Maxa\Ondrej\Discord;

use Discord\OAuth\Discord;
use Discord\OAuth\DiscordRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
    public function revokeToken(string $token): void
    {
        $client = new Client; 
        $response = $client->post($this->getRevokeTokenUrl(), [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'token' => $token,
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            $data = json_decode($response->getBody(), true);
            throw new DiscordRequestException("Error in response from Discord: $data[error]");
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultScopes()
    {
        return ['identify', 'email', 'guilds'];
    }
}