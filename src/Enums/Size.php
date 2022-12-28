<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static int SMALL()
 * @method static int MEDIUM()
 * @method static int BIG()
 */
enum Size: int
{
    use InvokableCases;

    case SMALL = 128;

    case MEDIUM = 256;

    case BIG = 512;
}
