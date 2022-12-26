<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Commands;

use StableDiffusionUI\SamplersGenerator\Enums\CommandName;
use StableDiffusionUI\SamplersGenerator\Processors\GenerateModels;

class Models extends Command
{
    protected string $processor = GenerateModels::class;

    protected function configure(): Command
    {
        return parent::configure()
            ->setName(CommandName::MODELS())
            ->setDescription('Generates a set of samples for several models.');
    }
}
