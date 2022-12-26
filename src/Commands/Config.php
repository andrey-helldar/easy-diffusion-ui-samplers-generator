<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Commands;

use StableDiffusion\SamplersGenerator\Enums\CommandName;
use StableDiffusion\SamplersGenerator\Enums\Option;
use StableDiffusion\SamplersGenerator\Processors\ConfigFiles;
use Symfony\Component\Console\Input\InputOption;

class Config extends Command
{
    protected string $processor = ConfigFiles::class;

    protected function configure(): Command
    {
        return $this
            ->setName(CommandName::MODELS())
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
