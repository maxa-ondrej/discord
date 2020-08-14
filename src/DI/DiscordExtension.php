<?php

namespace Maxa\Ondrej\Discord\DI;

use Maxa\Ondrej\Discord\ClientFactory;
use Maxa\Ondrej\Discord\MessageParserFactory;
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
			'logDir' => Expect::string(),
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
		$builder->addDefinition($this->prefix('streamHandler'))
			->setFactory(StreamHandler::class, [
				$this->config->logDir . '/discord.log',
				Logger::INFO
		]);
		$builder->addDefinition($this->prefix('logger'))
			->setFactory(Logger::class, [
				'discord'
			])
			->addSetup('pushHandler', [
				'@'.$this->prefix('streamHandler')
		]);
		$builder->addDefinition($this->prefix('clientFactory'))
			->setFactory(ClientFactory::class, [
				$this->config->token,
				'@'.$this->prefix('logger'),
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
