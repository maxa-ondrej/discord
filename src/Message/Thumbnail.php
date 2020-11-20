<?php

namespace Majksa\Discord\Message;

use Majksa\Discord\Collection\CollectionObject;

class Thumbnail extends CollectionObject {
    public ?string $url;
    public ?string $proxyUrl;
    public ?string $height;
    public ?string $width;
}