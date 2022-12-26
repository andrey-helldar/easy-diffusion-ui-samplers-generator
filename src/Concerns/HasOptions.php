<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Concerns;

use DragonCode\Contracts\DataTransferObject\DataTransferObject;
use StableDiffusion\SamplersGenerator\Commands\Command;
use StableDiffusion\SamplersGenerator\Enums\Option;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;

/** @mixin Command */
trait HasOptions
{
    protected function getOptions(): DataTransferObject|ImageProperties
    {
        return ImageProperties::fromArray([
            'activeTags'              => $this->option(Option::TAGS, []),
            'device'                  => $this->device(),
            'negativePrompt'          => $this->option(Option::NEGATIVE_PROMPT, ''),
            'originalPrompt'          => $this->option(Option::PROMPT),
            'path'                    => $this->option(Option::PATH, './build'),
            'seed'                    => $this->option(Option::SEED, $this->generateSeed()),
            'showPathInfo'            => $this->option(Option::SHOW_PATH, true),
            'useFaceCorrection'       => $this->option(Option::FIX_FACES, false),
            'useStableDiffusionModel' => $this->option(Option::MODEL),
            'width'                   => $this->getCellSize(),
            'height'                  => $this->getCellSize(),
        ]);
    }

    protected function option(Option $option, mixed $default = null): mixed
    {
        if ($this->input->hasOption($option->value)) {
            return $this->input->getOption($option->value) ?: $default;
        }

        return $default;
    }

    protected function generateSeed(): int
    {
        return random_int(1000000, 9999999999);
    }
}
