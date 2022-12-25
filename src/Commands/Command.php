<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Commands;

use DragonCode\Support\Facades\Instances\Instance;
use StableDiffusion\SamplersGenerator\Concerns\ValidateOptions;
use StableDiffusion\SamplersGenerator\Enums\Option;
use StableDiffusion\SamplersGenerator\Helpers\Output;
use StableDiffusion\SamplersGenerator\Processors\Processor;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends BaseCommand
{
    use ValidateOptions;

    protected InputInterface $input;

    protected Output $output;

    protected string $processor;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = Output::make($this->input, $output);

        $this->validateOptions();
        $this->info();
        $this->handle();

        return 0;
    }


    protected function configure(): Command
    {
        return $this;
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
        return new $this->processor(
            $this->output,
            $this->option(Option::PROMPT),
            $this->option(Option::NEGATIVE_PROMPT, ''),
            $this->option(Option::MODIFIERS, []),
            $this->option(Option::FIX_FACES, false),
            $this->option(Option::PATH, '.'),
            $this->option(Option::SEED, $this->generateSeed())
        );
    }

    protected function getClassBasename(object | string $class): string
    {
        return Instance::basename($class);
    }

    protected function option(Option $option, mixed $default = null): mixed
    {
        return $this->input->getOption($option->value) ?: $default;
    }

    protected function generateSeed(): int
    {
        return random_int(1000000, 9999999999);
    }
}
