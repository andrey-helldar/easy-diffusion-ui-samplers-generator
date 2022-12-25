<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Exceptions;

use RuntimeException;

class IncorrectOptionValueException extends RuntimeException
{
    public function __construct(string $name)
    {
        parent::__construct("Option \"$name\" is not defined or has an empty value.", 500);
    }
}
