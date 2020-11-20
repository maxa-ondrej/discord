<?php

namespace Majksa\Discord\Message;

use Majksa\Discord\Collection\CollectionObject;

class Video extends CollectionObject {
    public ?string $url;
    public ?string $height;
    public ?string $width;
}