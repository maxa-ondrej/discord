<?php

namespace Majksa\Discord;

use InvalidArgumentException;
use Majksa\Discord\Message\Message as SendMessage;
use RestCord\DiscordClient;
use RestCord\Model\Channel\Channel;
use RestCord\Model\Channel\Message;
use RestCord\Model\Emoji\Emoji;
use RestCord\Model\Guild\Role;

class Client {
	protected DiscordClient $discordClient;
	protected int $guildId;

	public function __construct(DiscordClient $discordClient, int $guildId)
	{
		$this->discordClient = $discordClient;
		$this->guildId = $guildId;
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

	public function getRole(int $id): Role
	{
		foreach ($this->getAllRoles as $role) {
			if($role->id == $id) {
				return $role;
			}
		}
		throw new InvalidArgumentException("Role not found.", 404);
	}

	public function getAllRoles()
	{
		return $this->discordClient->guild->getGuildRoles(
			[
				'guild.id' => $this->guildId
			]
		);
	}

	public function getChannel(int $id): Channel
	{
		return $this->discordClient->channel->getChannel(
			[
				'channel.id' => $id,
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
	public function sendChannelMessage(int $channel, SendMessage $message): array
	{
        $messageArray = $message->asArray();
        $messageArray['channel.id'] = $channel;
		return $this->discordClient->channel->createMessage($messageArray);
	}
}
