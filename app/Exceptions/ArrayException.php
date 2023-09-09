<?php

namespace App\Exceptions;

use Exception;

class ArrayException extends Exception
{
    private $_messages;

    public function __construct(array $_messages)
    {
        parent::__construct($message = '', $code = 0, $previous = null);
        $this->_messages = $_messages;
    }

    public function getMessagesArray(): array
    {
        return $this->_messages;
    }
}
