<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Enums;

enum Size: int
{
    case SMALL = 128;

    case MEDIUM = 256;

    case BIG = 512;
}
