<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Concerns;

use StableDiffusion\SamplersGenerator\Models\Models;
use StableDiffusion\SamplersGenerator\Requests\Models as ModelsRequest;

trait HasModels
{
    protected function models(): Models
    {
        return ModelsRequest::make()->get();
    }
}
