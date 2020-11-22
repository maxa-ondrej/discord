<?php


namespace Majksa\Discord\Exception;


use Exception;

class TokenRevokeException extends Exception
{
    public function __construct(array $data)
    {
        parent::__construct($data['error'] ?? $data['message'], $data['code']);
    }

}