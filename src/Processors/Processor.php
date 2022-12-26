<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Processors;

use StableDiffusion\SamplersGenerator\Concerns\HasSystemInfo;
use StableDiffusion\SamplersGenerator\Helpers\Output;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;
use StableDiffusion\SamplersGenerator\Services\Config;
use StableDiffusion\SamplersGenerator\Services\ImageGenerator;
use StableDiffusion\SamplersGenerator\Services\Storage;

abstract class Processor
{
    use HasSystemInfo;

    public function __construct(
        protected Output $output,
        protected ImageProperties $properties,
        protected Config $config = new Config(),
        protected ImageGenerator $image = new ImageGenerator(),
        protected Storage $filesystem = new Storage()
    ) {
    }

    abstract protected function run(): void;

    public function handle(): void
    {
        $this->output->timed(
            fn () => $this->run(),
            fn () => $this->finish()
        );
    }

    protected function finish(): void
    {
        $this->showPath();
    }

    protected function showPath(): void
    {
        if ($this->properties->showPathInfo) {
            $this->output->twoColumnDetail('Output Path', $this->properties->path);
            $this->output->emptyLine();
        }
    }

    protected function resolveProcessor(string $processor, ImageProperties $properties): self
    {
        return new $processor($this->output, $properties, $this->config, $this->image, $this->filesystem);
    }
}
