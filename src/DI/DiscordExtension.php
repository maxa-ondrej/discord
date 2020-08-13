<?php

namespace Maxa\Ondrej\Discord\DI;

use Nette\Schema\Expect;
use Nette\DI\CompilerExtension;
use Nette\Schema\Schema;

use Maxa\Ondrej\Discord\ProviderFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use RestCord\DiscordClient;

class DiscordExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'token' => Expect::string(),
			'version' => Expect::string()->default('1.0.0'),
			'logDir' => Expect::string(),
			'throwOnRatelimit' => Expect::bool()->default(false),
			'apiUrl' => Expect::string()->default('https://discordapp.com/api/v6'),
			'tokeType' => Expect::string()->default('Bot'),
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
		$builder->addDefinition($this->prefix('streamHandler'))
			->setFactory(StreamHandler::class, [[
				$this->config->logDir . '/discord.log',
				Logger::INFO
		]]);
		$builder->addDefinition($this->prefix('logger'))
			->setFactory(Logger::class, [
				'discord'
			])
			->addSetup('pushHandler', [
				$this->prefix('streamHandler')
		]);
		$builder->addDefinition($this->prefix('clientFactory'))
			->setFactory(DiscordClient::class, [[
				'token' => $this->config->token,
				'version' => $this->config->version,
				'logger' => $this->prefix('logger'),
				'throwOnRatelimit' => $this->config->throwOnRatelimit,
				'apiUrl' => $this->config->apiUrl,
				'tokenType' => $this->config->tokenType,
			]
		]);
	}
}
