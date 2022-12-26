<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Models;

use DragonCode\SimpleDataTransferObject\DataTransferObject;

class Neurals extends DataTransferObject
{
    /** @var array<string> */
    public array $models = [];

    /** @var array<string> */
    public array $vae = [];

    protected $map = [
        'options.stable-diffusion' => 'models',
        'options.vae'              => 'vae',
    ];
}
