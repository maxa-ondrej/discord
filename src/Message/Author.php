<?php

namespace Maxa\Ondrej\Discord\Message;

use Maxa\Ondrej\Discord\Collection\CollectionObject;

class Author extends CollectionObject {
    public ?string $name;
    public ?string $url;
    public ?string $iconUrl;
    public ?string $proxyIconUrl;
}