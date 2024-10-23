<?php

namespace App\Exceptions;

class UnauthorizedException extends \Exception
{
    protected $message = 'Unauthorized access';
    protected $code = 401;
}
