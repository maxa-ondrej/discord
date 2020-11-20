<?php

namespace Majksa\Discord;

use Monolog\Logger;
use RestCord\DiscordClient;

/**
 * Factory for Discord Auth Provider
 */
class ClientFactory
{
	private string $token;
	private Logger $logger;
	private bool $throwOnRatelimit;
	private string $apiUrl;
	private string $tokenType;
	private int $guild;


	/**
	 * Factory constructor.
	 *
	 * @param string $token
	 * @param Logger $logger
	 * @param boolean $throwOnRatelimit
	 * @param string $apiUrl
	 * @param string $tokenType
	 * @param int $guild
	 */
	public function __construct(string $token, Logger $logger, bool $throwOnRatelimit, string $apiUrl, string $tokenType, int $guild) {
		$this->token = $token;
		$this->logger = $logger;
		$this->throwOnRatelimit = $throwOnRatelimit;
		$this->apiUrl = $apiUrl;
		$this->tokenType = $tokenType;
		$this->guild = $guild;
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
            'logger' => $this->logger,
            'throwOnRatelimit' => $this->throwOnRatelimit,
            'apiUrl' => $this->apiUrl,
            'tokenType' => $tokenType ?: $this->tokenType,
			]),
			$this->guild
		);
	}
}
