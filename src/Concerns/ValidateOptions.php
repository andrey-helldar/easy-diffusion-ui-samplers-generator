<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Concerns;

use StableDiffusion\SamplersGenerator\Commands\Command;
use StableDiffusion\SamplersGenerator\Enums\Option;
use StableDiffusion\SamplersGenerator\Exceptions\IncorrectOptionValueException;
use StableDiffusion\SamplersGenerator\Exceptions\UnknownModelException;

/** @mixin Command */
trait ValidateOptions
{
    protected function validateOptions(): void
    {
        foreach ($this->getDefinition()->getOptions() as $name => $option) {
            $value = $this->input->getOption($name);

            if ($option->isValueRequired() && empty($value)) {
                throw new IncorrectOptionValueException($name);
            }

            if ($name === Option::MODEL()) {
                $this->validateModel($value, $this->models()->models);
            }
        }
    }

    protected function validateModel(string $model, array $models): void
    {
        if (!in_array($model, $models)) {
            throw new UnknownModelException($model, $models);
        }
    }
}
