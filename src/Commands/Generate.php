<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Commands;

use StableDiffusion\SamplersGenerator\Enums\CommandName;
use StableDiffusion\SamplersGenerator\Enums\Option;
use StableDiffusion\SamplersGenerator\Processors\Generator;
use Symfony\Component\Console\Input\InputOption;

class Generate extends Command
{
    protected string $processor = Generator::class;

    protected function configure(): Command
    {
        return parent::configure()
            ->setName(CommandName::GENERATE())
            ->setDescription('Generates a set of samples for several neural networks.')
            ->addOption(
                Option::PROMPT(),
                null,
                InputOption::VALUE_REQUIRED,
                'Query string for image generation.'
            )
            ->addOption(
                Option::NEGATIVE_PROMPT(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Exclusion words for query generation.'
            )
            ->addOption(
                Option::MODIFIERS(),
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Image generation modifiers.'
            )
            ->addOption(
                Option::FIX_FACES(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Enable fix incorrect faces and eyes via GFPGAN.'
            )
            ->addOption(
                Option::PATH(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Path to save the generated samples. By default in the launch folder.'
            )
            ->addOption(
                Option::SEED(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Seed ID of an early generated image.'
            );
    }
}
