<?php

namespace Maxa\Ondrej\Discord\Message;

use Maxa\Ondrej\Discord\Collection\CollectionObject;

class Thumbnail extends CollectionObject {
    public ?string $url;
    public ?string $proxyUrl;
    public ?string $height;
    public ?string $width;
}