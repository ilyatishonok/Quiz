<?php

declare(strict_types=1);

namespace AppBundle\Validator;

abstract class Validator
{
    protected $errors;

    public function __construct()
    {
        $errors = array();
    }

    abstract public function validate(array $data): bool;
}
