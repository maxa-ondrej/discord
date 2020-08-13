<?php

namespace Maxa\Ondrej\Discord;


/**
 * Factory for Discord Auth Provider
 */
class ProviderFactory
{
	private string $clientId;
	private string $clientSecret;

	/**
	 * Factory constructor
	 *
	 * @param string $clientId
	 * @param string $clientSecret
	 * @param array $permissions
	 */
	public function __construct(string $clientId, string $clientSecret) {
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
