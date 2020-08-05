<?php

namespace Maxa\Ondrej\Discord;

use Maxa\Ondrej\Discord\Message\Message;

class Logger {
    protected Channel $channel;
    protected int $roleId;

    public function __construct(Channel $channel, int $roleId)
    {
        $this->channel = $channel;
        $this->roleId = $roleId;
    }

    /**
     * Logs Messages to log channel
     *
     * @param string $type
     * @return void
     */
    public function log(Message $message, bool $ping = false): void
    {
        $message->content .= $ping?'<@&'.$this->roleId.'>':'';
        $this->channel->send($message);
    }
}