<?php

namespace Majksa\Discord\Message;

use Majksa\Discord\Collection\CollectionObject;

class Author extends CollectionObject {
    public ?string $name;
    public ?string $url;
    public ?string $iconUrl;
    public ?string $proxyIconUrl;
}