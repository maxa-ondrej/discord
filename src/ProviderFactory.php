<?php

namespace Majksa\Discord;


/**
 * Factory for Discord Auth Provider
 */
class ProviderFactory
{
    /**
     * @var int
     */
	protected int $clientId;
    /**
     * @var string
     */
    protected string $clientSecret;
    /**
     * @var string
     */
    protected string $apiUrl;

    /**
     * Factory constructor
     *
     * @param int $clientId
     * @param string $clientSecret
     */
	public function __construct(int $clientId, string $clientSecret, string $apiUrl) {
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->apiUrl = $apiUrl;
	}

	/**
	 * Creates new Provider
	 *
	 * @param string $redirectUri
	 * @return Provider
	 */
	public function create(string $redirectUri): Provider
	{
		return new Provider([
			'clientId' => $this->clientId,
			'clientSecret' => $this->clientSecret,
			'apiUrl' => $this->apiUrl,
			'redirectUri' => $redirectUri,
		]);
	}
}
