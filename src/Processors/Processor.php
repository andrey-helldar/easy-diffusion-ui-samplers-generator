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

    protected bool $showPathInfo = true;

    public function __construct(
        protected Output $output,
        protected ImageProperties $properties,
        protected Config $config = new Config(),
        protected ImageGenerator $image = new ImageGenerator(),
        protected Storage $filesystem = new Storage()
    ) {
        $this->showPathInfo = $this->properties->showPathInfo;
    }

    abstract protected function run(): void;

    public function handle(): void
    {
        $this->output->timed(
            fn () => $this->run(),
            fn () => $this->showPath()
        );
    }

    protected function showPath(): void
    {
        if ($this->showPathInfo && $path = $this->getRealPath()) {
            $this->output->twoColumnDetail('Output Path', $path);
        }
    }

    protected function resolveProcessor(string $processor, ImageProperties $properties): self
    {
        return new $processor($this->output, $properties, $this->config, $this->image, $this->filesystem);
    }

    protected function getRealPath(): string | bool
    {
        return realpath($this->properties->path . '/' . $this->properties->getInitiatedAt());
    }
}
