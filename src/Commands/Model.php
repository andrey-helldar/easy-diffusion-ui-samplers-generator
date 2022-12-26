<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Commands;

use StableDiffusion\SamplersGenerator\Enums\CommandName;
use StableDiffusion\SamplersGenerator\Enums\Option;
use StableDiffusion\SamplersGenerator\Processors\GenerateModel;
use Symfony\Component\Console\Input\InputOption;

class Model extends Command
{
    protected string $processor = GenerateModel::class;

    protected function configure(): Command
    {
        return parent::configure()
            ->setName(CommandName::MODEL())
            ->setDescription('Generates a set of samples for one model.')
            ->addOption(
                Option::MODEL(),
                null,
                InputOption::VALUE_REQUIRED,
                'Model for generating samples.'
            );
    }
}
