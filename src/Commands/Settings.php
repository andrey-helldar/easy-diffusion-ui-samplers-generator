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
            )
            ->addOption(
                Option::OUTPUT_FORMAT(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Sets the file export format: jpeg or png. By default, jpeg.'
            )
            ->addOption(
                Option::OUTPUT_QUALITY(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Specifies the percentage quality of the output image. By default, 75.'
            )
            ->addOption(
                Option::SINGLE_MODEL(),
                null,
                InputOption::VALUE_NONE,
                'Specifies whether the sampler will be generated on the model from the configuration. By default, samplers are generated on all available models.'
            );
    }
}
