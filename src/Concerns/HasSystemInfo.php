<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Concerns;

use StableDiffusionUI\SamplersGenerator\Models\Neurals;
use StableDiffusionUI\SamplersGenerator\Requests\SystemInfo;

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
