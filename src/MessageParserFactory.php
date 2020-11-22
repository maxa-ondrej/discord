<?php

namespace Majksa\Discord;

use RestCord\DiscordClient;

/**
 * Parses Discord Messages
 */
class MessageParserFactory
{
    private DiscordClient $client;

    public function __construct(ClientFactory $clientFactory) {
        $this->client = $clientFactory->create();
    }

    public function create(string $content, int $guildId): MessageParser
    {
        return new MessageParser($content, $guildId, $this->client);
    }
}