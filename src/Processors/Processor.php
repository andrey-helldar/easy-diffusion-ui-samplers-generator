<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Processors;

use DragonCode\Support\Facades\Filesystem\Directory;
use StableDiffusion\SamplersGenerator\Helpers\Output;
use StableDiffusion\SamplersGenerator\Services\Config;
use StableDiffusion\SamplersGenerator\Services\Filesystem;
use StableDiffusion\SamplersGenerator\Services\ImageGenerator;

abstract class Processor
{
    public function __construct(
        protected Output $output,
        protected string $prompt,
        protected string $negativePrompt,
        protected array $modifiers,
        protected bool $fixFaces,
        protected string $path,
        protected int $seed,
        protected Config $config = new Config(),
        protected ImageGenerator $image = new ImageGenerator(),
        protected Filesystem $filesystem = new Filesystem()
    ) {
        $this->resolvePath();
    }

    abstract public function handle(): void;

    protected function resolvePath(): void
    {
//        $this->path = $this->ensureDirectory($this->path . '/build/' . $this->today());
        $this->path = $this->ensureDirectory($this->path . '/build');

        $this->output->line('Output Path: ' . $this->path);
    }

    protected function ensureDirectory(string $path): string
    {
        Directory::ensureDirectory($path);

        return realpath($path);
    }

    protected function today(): string
    {
        return date('Y-m-d-H-i-s');
    }
}
