<?php

namespace techweb\core\exception;

class FileTypeException extends \Exception
{
	const ERROR_CODE = 3;

    public function __construct($message)
    {
        parent::__construct($message, self::ERROR_CODE);
    }

}