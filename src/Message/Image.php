<?php

namespace Maxa\Ondrej\Discord\Message;

use Maxa\Ondrej\Discord\Collection\CollectionObject;

class Image extends CollectionObject {
    public ?string $image;
    public ?string $proxyUrl;
    public ?string $height;
    public ?string $width;
}