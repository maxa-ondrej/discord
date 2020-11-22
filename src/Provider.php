<?php

namespace Majksa\Discord;

use GuzzleHttp\Client;
use HttpRequestException;
use League\OAuth2\Client\Provider\GenericProvider;


/**
 * Service provider.
 */
class Provider extends GenericProvider
{

    /**
     * @var string
     */
    protected string $revokeTokenUrl;

    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options + [
            'urlAuthorize' => $options['apiUrl'] . '/oauth2/authorize',
            'urlAccessToken' => $options['apiUrl'] . '/oauth2/token',
            'urlResourceOwnerDetails' => $options['apiUrl'] . '/users/@me',
        ], $collaborators);
        $this->revokeTokenUrl = $options['apiUrl'] . '/oauth2/token/revoke';
    }

    /**
     * Gets url for revoking access token.
     *
     * @return string
     */
    public function getRevokeTokenUrl(): string
    {
        return $this->revokeTokenUrl;
    }

    /**
     * Revokes token
     *
     * @param string $token
     * @throws HttpRequestException
     */
    public function revokeToken(string $token): void
    {
        $client = new Client; 
        $response = $client->post($this->revokeTokenUrl, [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'token' => $token,
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            $data = json_decode($response->getBody(), true);
            throw new HttpRequestException("Error in response from Discord: $data[error]");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultScopes()
    {
        return ['identify', 'email', 'guilds'];
    }
}