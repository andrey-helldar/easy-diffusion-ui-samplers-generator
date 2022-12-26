<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Requests;

use DragonCode\Support\Concerns\Makeable;
use DragonCode\Support\Facades\Helpers\Arr;
use StableDiffusion\SamplersGenerator\Models\Neurals;
use StableDiffusion\SamplersGenerator\Services\Http;

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
        $response = $this->http->get('/get/system_info');

        $devices = Arr::get($response, 'devices.active', []);

        return Arr::of($devices)
            ->map(fn (array $device) => $device['name'])
            ->implode(', ')
            ->toString();
    }
}
