<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Exceptions;

use RuntimeException;

class UnknownModelException extends RuntimeException
{
    public function __construct(string $name, array $available)
    {
        $models = implode(', ', $available);

        parent::__construct($name . ' model not found. Available models: ' . $models, 500);
    }
}
