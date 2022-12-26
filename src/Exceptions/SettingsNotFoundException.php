<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Exceptions;

use RuntimeException;

class SettingsNotFoundException extends RuntimeException
{
    public function __construct(string $directory)
    {
        $directory = realpath($directory);

        parent::__construct($this->message($directory), 500);
    }

    protected function message(string $directory): string
    {
        return "The specified folder \"$directory\" does not contain json configuration files or these files are corrupted.";
    }
}
