<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Commands;

use StableDiffusionUI\SamplersGenerator\Enums\CommandName;
use StableDiffusionUI\SamplersGenerator\Enums\Option;
use StableDiffusionUI\SamplersGenerator\Processors\Settings as SettingsProcessor;
use Symfony\Component\Console\Input\InputOption;

class Settings extends Command
{
    protected string $processor = SettingsProcessor::class;

    protected function configure(): Command
    {
        return $this
            ->setName(CommandName::SETTINGS())
            ->setDescription('Sampler generation from saved Stable Diffusion configuration files.')
            ->addOption(
                Option::PATH(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Path to save the generated samples. By default, in the "build" subfolder inside the current directory.',
                './build'
            );
    }
}
