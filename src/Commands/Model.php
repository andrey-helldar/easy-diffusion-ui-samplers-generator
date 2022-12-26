<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Commands;

use StableDiffusionUI\SamplersGenerator\Enums\CommandName;
use StableDiffusionUI\SamplersGenerator\Enums\Option;
use StableDiffusionUI\SamplersGenerator\Processors\GenerateModel;
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
            )
            ->addOption(
                Option::SHOW_PATH(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Determines whether to display the path to the save folder on the screen.',
                true
            );
    }
}
