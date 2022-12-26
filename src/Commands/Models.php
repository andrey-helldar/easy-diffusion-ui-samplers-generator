<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Commands;

use StableDiffusion\SamplersGenerator\Enums\CommandName;
use StableDiffusion\SamplersGenerator\Processors\GenerateModels;

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
