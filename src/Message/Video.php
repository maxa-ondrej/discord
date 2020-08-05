<?php

namespace Maxa\Ondrej\Discord\Message;

use Maxa\Ondrej\Discord\Collection\CollectionObject;

class Video extends CollectionObject {
    public ?string $url;
    public ?string $height;
    public ?string $width;
}