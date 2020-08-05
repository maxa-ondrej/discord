<?php

namespace Maxa\Ondrej\Discord;

use Maxa\Ondrej\Discord\Message\Message;
use RestCord\DiscordClient;
use RestCord\Interfaces\Channel as DiscordChannel;

class Channel {
    protected DiscordChannel $channel;
    protected int $channelId;

    
    public function __construct(DiscordClient $client, int $channelId)
    {
        $this->channel = $client->channel;
        $this->channelId = $channelId;
    }

    /**
     * Sends Messages
     *
     * @param string $type
     * @return void
     */
    public function send(Message $message): void
    {
        $this->channel->createMessage(array_merge(
                ['channel.id' => $this->channelId],
                $message->asArray()
            )
        );
    }
}