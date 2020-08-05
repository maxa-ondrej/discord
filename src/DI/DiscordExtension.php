<?php

namespace Maxa\Ondrej\Discord\DI;

use Nette\Schema\Expect;
use Nette\DI\CompilerExtension;
use Nette\Schema\Schema;

use League\OAuth2\Client\Provider\Discord;
use Maxa\Ondrej\Discord\Logger;
use RestCord\DiscordClient;

class DiscordExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'token' => Expect::string(),
			'guild' => Expect::structure([
				'id' => Expect::int(),
                'logger' => Expect::structure([
                    'channel' => Expect::int(),
                    'role' => Expect::int(),
                ]),
            ]),
			'client' => Expect::structure([
				'id' => Expect::int(),
				'secret' => Expect::string(),
            ]),
			'permissions' => Expect::arrayOf('string'),
			'redirect' => Expect::string(),
		]);
	}

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('provider'))
			->setFactory(Discord::class, [[
				'clientId' => $this->config->client->id,
				'clientSecret' => $this->config->client->secret,
				'redirectUri' => $this->config->redirect,
				'scope' => implode(' ', $this->config->permissions)
				]]);
		$builder->addDefinition($this->prefix('client'))
			->setFactory(DiscordClient::class, [[
				'token' => $this->config->token
        ]]);
		$builder->addDefinition($this->prefix('channel'))
			->setFactory(Logger::class, [
                '@discord.client',
                $this->guild->logger->channel
        ]);
		$builder->addDefinition($this->prefix('logger'))
			->setFactory(Logger::class, [
                '@discord.channel',
                $this->guild->logger->role
        ]);
	}
}
