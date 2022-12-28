<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Requests;

use DragonCode\Support\Concerns\Makeable;
use DragonCode\Support\Facades\Filesystem\Path;
use DragonCode\Support\Facades\Helpers\Arr;
use StableDiffusionUI\SamplersGenerator\Models\Neurals;
use StableDiffusionUI\SamplersGenerator\Services\Http;
use StableDiffusionUI\SamplersGenerator\Services\Parser;

class SystemInfo
{
    use Makeable;

    public function __construct(
        protected Http $http = new Http()
    ) {
    }

    public function models(): Neurals
    {
        $response = $this->http->get('/get/models');

        return Neurals::make($response);
    }

    public function device(): string
    {
        $devices = Arr::get($this->getSystemInfo(), 'devices.active', []);

        return Arr::of($devices)
            ->map(fn (array $device) => $device['name'])
            ->implode(', ')
            ->toString();
    }

    /**
     * @return array<string>
     */
    public function samplers(): array
    {
        $directory = Arr::get($this->getSystemInfo(), 'default_output_dir');

        $path = Path::dirname(Path::dirname($directory));

        return Parser::make($path . '/sd-ui-files/ui/index.html')->getSelect('sampler_name');
    }

    protected function getSystemInfo(): array
    {
        return $this->http->get('/get/system_info');
    }
}
