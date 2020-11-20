<?php

namespace Majksa\Discord\Message;

use Majksa\Discord\Collection\CollectionObject;
use Majksa\Discord\Collection\ArrayObject;

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