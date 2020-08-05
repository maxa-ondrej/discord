<?php

namespace Maxa\Ondrej\Discord\Message;

use Maxa\Ondrej\Discord\Collection\CollectionObject;

class Field extends CollectionObject {
    public string $name;
    public string $value;
    public ?bool $inline;

    public function __construct(string $name, string $value) {
        $this->name = $name;
        $this->value = $value;
    }
}