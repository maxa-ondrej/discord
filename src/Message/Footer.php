<?php

namespace Majksa\Discord\Message;

use Majksa\Discord\Collection\CollectionObject;

class Footer extends CollectionObject {
    public string $text;
    public ?string $iconUrl;
    public ?string $proxyIconUrl;

    public function __construct(string $text) {
        $this->text = $text;
    }
}