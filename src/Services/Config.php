<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services;

use DragonCode\Support\Facades\Filesystem\File;
use DragonCode\Support\Facades\Filesystem\Path;
use DragonCode\Support\Facades\Helpers\Arr;

class Config
{
    protected string $path = __DIR__ . '/../../config';

    protected array $registry = [];

    public function __construct()
    {
        $this->load();
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->registry, $key, $default);
    }

    protected function load(): void
    {
        foreach ($this->configFiles() as $filename) {
            $key = Path::filename($filename);

            $this->registry[$key] = require $this->path . '/' . $filename;
        }
    }

    protected function configFiles(): array
    {
        return File::names($this->path);
    }
}
