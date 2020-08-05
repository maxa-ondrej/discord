<?php

namespace Maxa\Ondrej\Discord\Message;

use Maxa\Ondrej\Discord\Collection\ArrayObject;
use Maxa\Ondrej\Discord\Collection\CollectionObject;

class Embed extends CollectionObject {
    public ?string $title;
    public ?string $type;
    public ?string $description;
    public ?string $url;
    public $timestap;
    public ?int $color;
    public ?Footer $footer;
    public ?Image $image;
    public ?Thumbnail $thumbnail;
    public ?Video $video;
    public ?Provider $provider;
    public ?Author $author;
    public ArrayObject $fields;

    public function __construct() {
        $this->fields = new ArrayObject(Field::class);
    }
}