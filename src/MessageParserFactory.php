<?php

namespace Maxa\Ondrej\Discord;

/**
 * Parses Discord Messages
 */
class MessageParserFactory
{
    private Client $client;

    public function __construct(ClientFactory $clientFactory) {
        $this->client = $clientFactory->create();
    }

    public function create(string $content): MessageParser
    {
        return new MessageParser($content, $this->client);
    }
}