<?php

namespace App\Exceptions;

use Exception;

class FailedToGenerateUniqueShortLink extends Exception
{
    public function __construct()
    {
        parent::__construct('Failed to generate unique short link');
    }
}