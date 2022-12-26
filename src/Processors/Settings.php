<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Processors;

use DragonCode\Contracts\DataTransferObject\DataTransferObject;
use DragonCode\Support\Facades\Filesystem\File;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Str;
use StableDiffusion\SamplersGenerator\Exceptions\SettingsNotFoundException;
use StableDiffusion\SamplersGenerator\Helpers\Schemas\SettingsSchema;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;

class Settings extends Processor
{
    public function handle(): void
    {
        $this->run();
    }

    protected function run(): void
    {
        if ($files = $this->files()) {
            $this->process($this->getPath(), $files, time());

            return;
        }

        throw new SettingsNotFoundException($this->getPath());
    }

    protected function process(string $directory, array $files, int $sessionId): void
    {
        foreach ($files as $file) {
            $path = realpath($directory . '/' . $file);

            $this->isValid($path)
                ? $this->runNetwork($path, $file, $sessionId)
                : $this->warning($path);
        }
    }

    protected function runNetwork(string $path, string $filename, int $sessionId): void
    {
        $this->output->info('Run: ' . $filename);

        $properties = $this->createProperties($this->load($path), $sessionId);

        $this->resolveProcessor(GenerateModels::class, $properties)->handle();
    }

    protected function warning(string $file): void
    {
        $this->output->warn("File \"$file\" contains invalid settings. Skipped.");
    }

    protected function isValid(string $filename): bool
    {
        return SettingsSchema::make($this->output)->isValid($filename);
    }

    protected function createProperties(array $items, int $sessionId): DataTransferObject | ImageProperties
    {
        return ImageProperties::fromArray($items)
            ->setSessionId($sessionId)
            ->setPath($this->properties->path)
            ->setDevice($this->properties->device)
            ->resetNumOutputs()
            ->resetShowOnlyFilteredImage()
            ->resetStreamImageProgress()
            ->resetStreamProgressUpdates();
    }

    protected function load(string $path): array
    {
        return Arr::ofFile($path)->toArray();
    }

    protected function files(): array
    {
        return File::names($this->getPath(), fn (string $filename) => Str::endsWith($filename, 'json'));
    }

    protected function getPath(): string
    {
        return $this->properties->path;
    }
}
