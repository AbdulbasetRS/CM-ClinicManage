<?php

namespace App\Exceptions;

use Exception;

class EmailNotVerifiedException extends Exception
{
    public function __construct(public int $userId)
    {
        parent::__construct('email_not_verified');
    }
}
