<?php

namespace Maxa\Ondrej\Discord\Message;

use Maxa\Ondrej\Discord\Collection\CollectionObject;
use Maxa\Ondrej\Discord\Collection\ArrayObject;

class AllowedMentions extends CollectionObject {
    public ArrayObject $parse;
    public ArrayObject $roles;
    public ArrayObject $users;

    public function __construct() {
        $this->parse = new ArrayObject(self::class);
        $this->roles = new ArrayObject('integer');
        $this->users = new ArrayObject('integer');
    }
}