<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Processors;

use StableDiffusionUI\SamplersGenerator\Concerns\HasSizes;
use StableDiffusionUI\SamplersGenerator\Concerns\HasSystemInfo;
use StableDiffusionUI\SamplersGenerator\Helpers\Output;
use StableDiffusionUI\SamplersGenerator\Models\ImageProperties;
use StableDiffusionUI\SamplersGenerator\Services\Config;
use StableDiffusionUI\SamplersGenerator\Services\ImageGenerator;
use StableDiffusionUI\SamplersGenerator\Services\Storage;

abstract class Processor
{
    use HasSizes;
    use HasSystemInfo;

    protected bool $showPathInfo = true;

    abstract protected function run(): void;

    public function __construct(
        protected Output $output,
        protected ImageProperties $properties,
        protected Config $config = new Config(),
        protected ImageGenerator $image = new ImageGenerator(),
        protected Storage $filesystem = new Storage(
        )
    ) {
        $this->showPathInfo = $this->properties->showPathInfo;
    }

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

    protected function getRealPath(): bool|string
    {
        return realpath($this->properties->path . '/' . $this->properties->getInitiatedAt());
    }
}
