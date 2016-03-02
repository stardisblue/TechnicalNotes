<?php

namespace techweb\core\exception;

class IOException extends \Exception
{
	const ERROR_CODE = 0;

    public function __construct($message)
    {
        parent::__construct($message, self::ERROR_CODE);
    }

}