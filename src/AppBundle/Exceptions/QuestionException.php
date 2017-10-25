<?php

declare(strict_types=1);

namespace AppBundle\Exceptions;

use Symfony\Component\Config\Definition\Exception\Exception;

class QuestionException extends Exception
{
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return parent::__toString();
    }
}
