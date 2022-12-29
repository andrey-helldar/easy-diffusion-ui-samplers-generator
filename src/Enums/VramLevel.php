<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string HIGH()
 * @method static string LOW()
 * @method static string BALANCED()
 */
enum VramLevel: string
{
    use InvokableCases;

    case HIGH = 'high';

    case LOW = 'low';

    case BALANCED = 'balanced';
}
