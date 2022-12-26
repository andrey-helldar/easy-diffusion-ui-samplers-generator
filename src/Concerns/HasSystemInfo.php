<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Concerns;

use StableDiffusion\SamplersGenerator\Models\Neurals;
use StableDiffusion\SamplersGenerator\Requests\SystemInfo;

trait HasSystemInfo
{
    protected function models(): Neurals
    {
        return SystemInfo::make()->models();
    }

    protected function device(): string
    {
        return SystemInfo::make()->device();
    }
}
