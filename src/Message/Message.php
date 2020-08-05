<?php

namespace Maxa\Ondrej\Discord\Message;

use Maxa\Ondrej\Discord\Collection\CollectionObject;

class Message extends CollectionObject {
    public string $content = '';
    public $nonce;
    public ?bool $tts;
    public ?string $file;
    public ?Embed $embed;
    public ?string $playloadJson;
    public ?AllowedMentions $allowedMentions;
}