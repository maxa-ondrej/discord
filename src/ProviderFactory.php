<?php

namespace Majksa\Discord;


/**
 * Factory for Discord Auth Provider
 */
class ProviderFactory
{
	private int $clientId;
	private string $clientSecret;

    /**
     * Factory constructor
     *
     * @param int $clientId
     * @param string $clientSecret
     */
	public function __construct(int $clientId, string $clientSecret) {
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
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
			'redirectUri' => $redirectUri,
		]);
	}
}
