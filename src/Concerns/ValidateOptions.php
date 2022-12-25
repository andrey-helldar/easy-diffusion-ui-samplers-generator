<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Concerns;

use StableDiffusion\SamplersGenerator\Commands\Command;
use StableDiffusion\SamplersGenerator\Exceptions\IncorrectOptionValueException;

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
        }
    }
}
