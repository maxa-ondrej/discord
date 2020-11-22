<?php

namespace Majksa\Discord;

use GuzzleHttp\Command\Exception\CommandClientException;
use Nette\Utils\Html;
use RestCord\DiscordClient;
use stdClass;

/**
 * Parses Discord Messages
 */
class MessageParser
{
	public const FORMAT_REGEXS = [
		'multiline' => [
			'/```(?:.*'.self::NEW_LINE.'?'.self::NEW_LINE_CHAR.'?)?([^`]+)```/' => '<pre><code>$1</code></pre>',
			'/^&gt; ([^\n]+)/m' => '<blockquote class="blockquote">$1</blockquote>',
        ],
        'singleline' => [
			'/\*\*([^\*]+)\*\*/' => '<b>$1</b>',
			'/\*([^\*]+)\*/' => '<i>$1</i>',
			'/__([^\*]+)__/' => '<u>$1</u>',
			'/~~([^\*]+)~~/' => '<s>$1</s>',
			'/`([^`]+)`/' => '<code>$1</code>',
		]
    ];
    
    public const NEW_LINE = '<br>';
	public const NEW_LINE_CHAR = '
	';

    /**
     * @var string
     */
    public string $content;
    /**
     * @var int
     */
	private int $guildId;
    /**
     * @var DiscordClient
     */
	private DiscordClient $client;

	public function __construct(string $content, int $guildId, DiscordClient $client) {
		$this->content = $content;
		$this->guildId = $guildId;
		$this->client = $client;
	}

	/**
	 * Replaces <:{$name}:{$id}> with :{$name}:
	 */
	public function parse(): void
	{
		$this->content = htmlentities($this->content);
		$this->content = str_replace('<br />', self::NEW_LINE, nl2br($this->content));
		$this->parseUserMentions();
		$this->parseChannelMentions();
		$this->parseRoleMentions();
		$this->parseEmojis();
		$this->parseFormat();
		$this->removeBrTagsFromPre();
	}

	/**
	 * Replaces <@!{$id}> with {$username}#{$discriminator}
	 */
	public function parseUserMentions(): void
	{
		preg_match_all("/&lt;@!?(\d{18})&gt;/", $this->content, $mentions, PREG_SET_ORDER);
		foreach($mentions as $mention) {
			try {
				$member = $this->client->guild->getGuildMember([
                    'guild.id' => $this->guildId,
                    'user.id' => $mention[1],
                ]);
				$user = $member->user;
			} catch (CommandClientException $e) {
				if($e->getResponse()->getStatusCode() === 404) {
					$user = $this->client->user->getUser([
                        'user.id' => $mention[1],
                    ]);
				} else {
					throw $e;
				}
			}
			$el = Html::el('a');
			$el->class = 'btn btn-link mention';
			$el[] = '@'.(isset($member) && !is_null($member->nick) ? $member->nick : $user->username);		
			$el->title = $user->username . '#' . $user->discriminator;
			$this->content = str_replace($mention[0], $el, $this->content);
		}
	}

	/**
	 * Replaces <@!{$id}> with {$username}#{$discriminator}
	 */
	public function parseChannelMentions(): void
	{
		preg_match_all("/&lt;#(\d{18})&gt;/", $this->content, $mentions, PREG_SET_ORDER);
		foreach($mentions as $mention) {
			try {
				$channel = $this->client->channel->getChannel([
				    'channel.id' => $mention[1]
                ]);
			} catch (CommandClientException $e) {
				if($e->getResponse()->getStatusCode() === 404) {
					$channel = new stdClass();
					$channel->name = 'deleted-channel';
				} else {
					throw $e;
				}
			}
			$el = Html::el('a');
			$el->class = 'btn btn-link mention';
			$el[] = "#{$channel->name}";		
			$el->title = $mention[1];
			$this->content = str_replace($mention[0], $el, $this->content);
		}
	}

	/**
	 * Replaces <@!{$id}> with {$username}#{$discriminator}
	 */
	public function parseRoleMentions(): void
	{
		preg_match_all("/&lt;@&(\d{18})&gt;/", $this->content, $mentions, PREG_SET_ORDER);
		foreach($mentions as $mention) {
            $roles = $this->client->guild->getGuildRoles([
                'guild.id' => $this->guildId
            ]);
            $role = new stdClass();
            $role->name = 'deleted-role';
            foreach ($roles as $guildRole) {
                if ($guildRole->id == $mention[1]) {
                    $role = $guildRole;
                }
            }
			$el = Html::el('a');
			$el->class = 'btn btn-link mention';
			$el[] = "#{$role->name}";		
			$el->title = $mention[1];
			$this->content = str_replace($mention[0], $el, $this->content);
		}
	}

	/**
	 * Replaces <:{$name}:{$id}> with <img> tag
	 */
	public function parseEmojis(): void
	{
		preg_match_all("/&lt;:([^:]+):(\d{18})&gt;/", $this->content, $emojis, PREG_SET_ORDER);
		foreach($emojis as $emoji) {
			$el = Html::el('img');
			$el->src = 'https://cdn.discordapp.com/emojis/'.$emoji[2];
			if(@getimagesize("https://cdn.discordapp.com/emojis/$emoji[2].gif?size=16")) {
				$el->src .= '.gif';
			} else {
				$el->src .= '.png';
			}
			$el->src .= "?size=16";
			$el->alt = ':'.$emoji[1].':';
			$el->title = $emoji[1];
			$this->content = str_replace($emoji[0], $el, $this->content);
		}
	}

    /**
     * Replaces content using self::FORMAT_REGEXS
     */
	public function parseFormat(): void
	{
		foreach (self::FORMAT_REGEXS as $type => $regexes) {
            foreach ($regexes as $regex => $replace) {
                if($type === 'singleline') {
                    $lines = explode(self::NEW_LINE, $this->content);
                    foreach ($lines as $key => $line) {
                        $lines[$key] = preg_replace($regex, $replace, $line);
                    }
                    $this->content = implode(self::NEW_LINE, $lines);
                } else {
                    $this->content = (string) preg_replace($regex, $replace, $this->content);
                }
            }
		}
	}

    /**
     * Replaces content using self::FORMAT_REGEXS
     */
	public function removeBrTagsFromPre(): void
	{
		preg_match_all('/<pre>[\s\S]*?<\/pre>/', $this->content, $matches);
		foreach ($matches[0] as $match) {
			$this->content = str_replace($match, str_replace('<br>','', $match), $this->content);
		}
	}
}
