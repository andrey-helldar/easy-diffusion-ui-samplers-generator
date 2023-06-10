<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Enums;

enum VramLevel: string
{
    case HIGH     = 'high';
    case LOW      = 'low';
    case BALANCED = 'balanced';
}
