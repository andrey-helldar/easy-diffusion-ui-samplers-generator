<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Exceptions;

use RuntimeException;

class SettingsNotFoundException extends RuntimeException
{
    public function __construct(string $directory)
    {
        $directory = realpath($directory);

        parent::__construct("JSON files of saved settings not found in \"$directory\" folder", 500);
    }
}
