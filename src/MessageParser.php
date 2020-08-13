<?php

namespace Maxa\Ondrej\Discord;

use GuzzleHttp\Command\Exception\CommandClientException;
use Nette\Utils\Html;

/**
 * Parses Discord Messages
 */
class MessageParser
{
	public string $content;
	public const FORMAT_REGEXS = [
		'multiline' => [
			'/```(?:.*'.self::NEW_LINE.'?'.self::NEW_LINE_CHAR.'?)?([^`]+)```/' => '<pre><code>$1</code></pre>',
			'/^&gt; ([^\n]+)/m' => '<blockquote class="blockquote">$1</blockquote>',
        ],
        'singleline' => [
			'/\*\*([^\*]+)\*\*/' => '<b>$1</b>',
			'/\*([^\*]+)\*/' => '<i>$1</i>',
			'/__([^\*]+)__/' => '<u>$1</u>',
			'/~~([^\*]+)~~/' => '<strike>$1</strike>',
			'/`([^`]+)`/' => '<code>$1</code>',
		]
    ];
    
    public const NEW_LINE = '<br>';
	public const NEW_LINE_CHAR = '
	';

	private Client $client;


	public function __construct(string $content, Client $client) {
		$this->content = $content;
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
		$this->parseEmojis();
		$this->parseFormat();
		$this->removeBrTagsFromPre();
	}

	/**
	 * Replaces <@!{$id}> with {$username}#{$discriminator}
	 */
	public function parseUserMentions(): void
	{
		preg_match_all("/&lt;@!(\d{18})&gt;/", $this->content, $mentions, PREG_SET_ORDER);
		foreach($mentions as $mention) {
			try {
				$member = $this->client->getGuildMember($mention[1]);
				$user = $member->user;
			} catch (CommandClientException $e) {
				if($e->getResponse()->getStatusCode() === 404) {
					$user = $this->client->getUser($mention[1]);
				} else {
					throw $e;
				}
			}
			$el = Html::el('a');
			$el->class = 'btn btn-link user';
			if (isset($member) && !is_null($member->nick)) {
				$el[] = '@'.$member->nick;
			} else {
				$el[] = '@'.$user->username;
			}			
			$el->title = $member->user->username . '#' . $member->user->discriminator;
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
                    $this->content = preg_replace($regex, $replace, $this->content);
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
