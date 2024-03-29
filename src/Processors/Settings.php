<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Processors;

use DragonCode\Contracts\DataTransferObject\DataTransferObject;
use DragonCode\Support\Facades\Filesystem\File;
use DragonCode\Support\Facades\Filesystem\Path;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Str;
use StableDiffusionUI\SamplersGenerator\Exceptions\SettingsNotFoundException;
use StableDiffusionUI\SamplersGenerator\Helpers\Schemas\SettingsSchema;
use StableDiffusionUI\SamplersGenerator\Models\ImageProperties;

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

            $this->runNetwork($path, $file, $sessionId, $this->configName($file));
        }
    }

    protected function runNetwork(string $path, string $filename, int $sessionId, string $configName): void
    {
        $this->output->info('Run: ' . $filename);

        $properties = $this->createProperties($this->load($path), $sessionId, $configName);

        $this->resolveProcessor(GenerateModels::class, $properties)->handle();
    }

    protected function isValid(string $filename): bool
    {
        return SettingsSchema::make($this->output)->isValid($filename);
    }

    protected function createProperties(array $items, int $sessionId, string $configName): DataTransferObject|ImageProperties
    {
        return ImageProperties::fromArray($items)
            ->setSize($this->getCellSize())
            ->setSessionId($sessionId)
            ->setConfigName($configName)
            ->setPath($this->properties->path)
            ->setDevice($this->properties->device)
            ->setSingleModel($this->properties->singleModel)
            ->setOutput(
                $this->getParam($items, 'reqBody.output_format', $this->properties->outputFormat),
                $this->getParam($items, 'reqBody.output_quality', $this->properties->outputQuality)
            )
            ->resetNumOutputs()
            ->resetShowOnlyFilteredImage()
            ->resetStreamImageProgress()
            ->resetStreamProgressUpdates();
    }

    protected function load(string $path): array
    {
        return Arr::ofFile($path)->toArray();
    }

    protected function configName(string $filename): string
    {
        return Path::filename($filename);
    }

    protected function files(): array
    {
        $files = File::names(
            $this->getPath(),
            fn (string $filename) => $this->isJsonFile($filename) && $this->isValid($filename)
        );

        return Arr::sort($files);
    }

    protected function isJsonFile(string $path): bool
    {
        return Str::endsWith($path, 'json');
    }

    protected function getPath(): string
    {
        return $this->properties->path;
    }

    protected function getParam(array $items, string $key, mixed $default): mixed
    {
        return Arr::get($items, $key, $default);
    }
}
