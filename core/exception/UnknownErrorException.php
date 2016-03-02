<?php

namespace techweb\core\exception;

class UnknownErrorException extends \Exception
{
    const ERROR_CODE = 5;

    public function __construct($message)
    {
        parent::__construct($message, self::ERROR_CODE);
    }

}