<?php

namespace Maxa\Ondrej\Discord;

use Monolog\Logger;
use RestCord\DiscordClient;

/**
 * Factory for Discord Auth Provider
 */
class ClientFactory
{
	private string $token;
	private string $version;
	private Logger $logger;
	private bool $throwOnRatelimit;
	private string $apiUrl;
	private string $tokenType;


	/**
	 * Factory constructor.
	 *
	 * @param string $token
	 * @param string $version
	 * @param Logger $logger
	 * @param boolean $throwOnRatelimit
	 * @param string $apiUrl
	 * @param string $tokenType
	 */
	public function __construct(string $token, string $version, Logger $logger, bool $throwOnRatelimit, string $apiUrl, string $tokenType) {
		$this->token = $token;
		$this->version = $version;
		$this->logger = $logger;
		$this->throwOnRatelimit = $throwOnRatelimit;
		$this->apiUrl = $apiUrl;
		$this->tokenType = $tokenType;
	}

	/**
	 * Creates new DiscordClient.
	 *
	 * @param string $token
	 * @param string $tokenType
	 * @return Client
	 */
	public function create(string $token = null, string $tokenType = null): Client
	{
		return new Client(
			new DiscordClient([
            'token' => $token ?: $this->token,
            'version' => $this->version,
            'logger' => $this->logger,
            'throwOnRatelimit' => $this->throwOnRatelimit,
            'apiUrl' => $this->apiUrl,
            'tokenType' => $tokenType ?: $this->tokenType,
			])
		);
	}
}
