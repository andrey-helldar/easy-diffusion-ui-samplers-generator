<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Commands;

use DragonCode\Support\Facades\Instances\Instance;
use StableDiffusion\SamplersGenerator\Concerns\HasOptions;
use StableDiffusion\SamplersGenerator\Concerns\HasSystemInfo;
use StableDiffusion\SamplersGenerator\Concerns\ValidateOptions;
use StableDiffusion\SamplersGenerator\Enums\Option;
use StableDiffusion\SamplersGenerator\Helpers\Output;
use StableDiffusion\SamplersGenerator\Processors\Processor;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Command extends BaseCommand
{
    use HasSystemInfo;
    use HasOptions;
    use ValidateOptions;

    protected InputInterface $input;

    protected Output $output;

    protected string $processor;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = Output::make($this->input, new SymfonyStyle($input, $output));

        $this->validateOptions();
        $this->info();
        $this->handle();

        return 0;
    }

    protected function configure(): Command
    {
        return $this
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
                Option::TAGS(),
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Image generation modifiers.'
            )
            ->addOption(
                Option::FIX_FACES(),
                null,
                InputOption::VALUE_NONE,
                'Enable fix incorrect faces and eyes via GFPGANv1.3.'
            )
            ->addOption(
                Option::PATH(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Path to save the generated samples. By default, in the "build" subfolder inside the current directory.',
                './build'
            )
            ->addOption(
                Option::SEED(),
                null,
                InputOption::VALUE_OPTIONAL,
                'Seed ID of an early generated image.'
            );
    }

    protected function handle(): void
    {
        $this->resolveProcessor()->handle();
    }

    protected function info(): void
    {
        $name = $this->getClassBasename($this->processor);

        $this->output->info($name);
    }

    protected function resolveProcessor(): Processor
    {
        return new $this->processor($this->output, $this->getOptions());
    }

    protected function getClassBasename(object | string $class): string
    {
        return Instance::basename($class);
    }
}
