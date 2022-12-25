<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string GENERATE()
 */
enum CommandName: string
{
    use InvokableCases;

    case GENERATE = 'generate';
}
