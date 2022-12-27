<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string MODEL()
 * @method static string MODELS()
 * @method static string SETTINGS()
 */
enum CommandName: string
{
    use InvokableCases;

    case MODEL = 'model';

    case MODELS = 'models';

    case SETTINGS = 'settings';
}
