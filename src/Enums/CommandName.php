<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string CONFIG()
 * @method static string MODEL()
 * @method static string MODELS()
 */
enum CommandName: string
{
    use InvokableCases;

    case CONFIG = 'config';
    case MODEL = 'model';
    case MODELS = 'models';
}
