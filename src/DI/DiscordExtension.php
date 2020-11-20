<?php

namespace Majksa\Discord\DI;

use Majksa\Discord\ClientFactory;
use Majksa\Discord\MessageParserFactory;
use Nette\Schema\Expect;
use Nette\DI\CompilerExtension;
use Nette\Schema\Schema;

use Majksa\Discord\ProviderFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use RestCord\DiscordClient;

class DiscordExtension extends CompilerExtension
{
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

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('providerFactory'))
			->setFactory(ProviderFactory::class, [
				'clientId' => $this->config->client->id,
				'clientSecret' => $this->config->client->secret
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
