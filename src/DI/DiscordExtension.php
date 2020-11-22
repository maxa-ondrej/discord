<?php

namespace Majksa\Discord\DI;

use Majksa\Discord\ClientFactory;
use Majksa\Discord\MessageParserFactory;
use Nette\Schema\Expect;
use Nette\DI\CompilerExtension;
use Nette\Schema\Schema;

use Majksa\Discord\ProviderFactory;

/**
 * Class DiscordExtension
 */
class DiscordExtension extends CompilerExtension
{
    /**
     * Creates schema for configuration.
     *
     * @return Schema
     */
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'token' => Expect::string(),
			'logger' => Expect::string(),
			'throwOnRatelimit' => Expect::bool()->default(false),
			'apiUrl' => Expect::string()->default('https://discordapp.com/api/v6'),
			'tokenType' => Expect::string()->default('Bot'),
			'guild' => Expect::int(),
			'client' => Expect::structure([
				'id' => Expect::int(),
				'secret' => Expect::string(),
			])
		]);
	}

    /**
     * Loads configuration from config file.
     */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('providerFactory'))
			->setFactory(ProviderFactory::class, [
				$this->config->client->id,
				$this->config->client->secret,
                $this->config->apiUrl,
		]);
		$builder->addDefinition($this->prefix('clientFactory'))
			->setFactory(ClientFactory::class, [
				$this->config->token,
                $this->config->logger,
				$this->config->throwOnRatelimit,
				$this->config->apiUrl,
				$this->config->tokenType,
				$this->config->guild,
		]);
		$builder->addDefinition($this->prefix('messageParserFactory'))
			->setFactory(MessageParserFactory::class, [
				'@'.$this->prefix('clientFactory')
		]);
	}
}
