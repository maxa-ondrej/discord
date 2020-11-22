<?php

namespace Majksa\Discord;

use Monolog\Logger;
use RestCord\DiscordClient;

/**
 * Factory for Discord Auth Provider
 */
class ClientFactory
{
    /**
     * @var string
     */
	private string $token;
    /**
     * @var Logger
     */
	private Logger $logger;
    /**
     * @var bool
     */
	private bool $throwOnRatelimit;
    /**
     * @var string
     */
	private string $apiUrl;
    /**
     * @var string
     */
	private string $tokenType;


	/**
	 * Factory constructor.
	 *
	 * @param string $token
	 * @param Logger $logger
	 * @param boolean $throwOnRatelimit
	 * @param string $apiUrl
	 * @param string $tokenType
	 */
	public function __construct(string $token, Logger $logger, bool $throwOnRatelimit, string $apiUrl, string $tokenType) {
		$this->token = $token;
		$this->logger = $logger;
		$this->throwOnRatelimit = $throwOnRatelimit;
		$this->apiUrl = $apiUrl;
		$this->tokenType = $tokenType;
	}

    /**
     * Creates new DiscordClient.
     *
     * @param string|null $token
     * @param string|null $tokenType
     * @return DiscordClient
     */
	public function create(?string $token = null, ?string $tokenType = null): DiscordClient
	{
		return new DiscordClient([
            'token' => $token ?: $this->token,
            'logger' => $this->logger,
            'throwOnRatelimit' => $this->throwOnRatelimit,
            'apiUrl' => $this->apiUrl,
            'tokenType' => $tokenType ?: $this->tokenType,
			]);
	}
}
