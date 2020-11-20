<?php

namespace Majksa\Discord\Message;

use Majksa\Discord\Collection\CollectionObject;

class Image extends CollectionObject {
    public ?string $image;
    public ?string $proxyUrl;
    public ?string $height;
    public ?string $width;
}