<?php

namespace Maxa\Ondrej\Discord;

use Maxa\Ondrej\Discord\Message\Message as SendMessage;
use RestCord\DiscordClient;
use RestCord\Model\Channel\Message;
use RestCord\Model\Emoji\Emoji;

class Client {
	protected DiscordClient $discordClient;

	public function __construct(DiscordClient $discordClient)
	{
		$this->discordClient = $discordClient;
	}

	public function getGuildMember(int $userId)
	{
		return $this->discordClient->guild->getGuildMember(
			[
				'guild.id' => $this->guildId,
				'user.id' => $userId
			]
		);
	}

	public function getUser(int $userId)
	{
		return $this->discordClient->user->getUser(
			[
				'user.id' => $userId
			]
		);
	}

	public function getEmoji(int $id): Emoji
	{
		return $this->discordClient->emoji->getGuildEmoji([
			'guild.id' => $this->guildId,
			'emoji.id' => $id,
		]);
	}

	public function getAllRoles()
	{
		return $this->discordClient->guild->getGuildRoles(
			[
				'guild.id' => $this->guildId
			]
		);
	}

	public function getChannelMessages(int $id, int $limit = 50): array
	{
		return $this->discordClient->channel->getChannelMessages(
			[
				'channel.id' => $id,
				'limit' => $limit,
			]
		);
	}

	public function getChannelMessage(int $channel, int $message): Message
	{
		return $this->discordClient->channel->getChannelMessage(
			[
				'channel.id' => $channel,
				'message.id' => $message,
			]
		);
	}

    /**
     * Sends message to channel.
     *
     * @param integer $channel
     * @param SendMessage $message
     * @return Message
     */
	public function sendChannelMessage(int $channel, SendMessage $message): Message
	{
        $messageArray = $message->asArray();
        $messageArray['channel.id'] = $channel;
		return $this->discordClient->channel->createChannelMessage($messageArray);
	}
}
