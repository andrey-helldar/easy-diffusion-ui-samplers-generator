<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Services;

use DragonCode\Support\Facades\Helpers\Arr;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;

class ImageGenerator
{
    public function __construct(
        protected Http $http = new Http()
    ) {
    }

    public function generate(ImageProperties $properties): string
    {
        $task = $this->createImage($properties);

        return $this->getImage($task);
    }

    protected function createImage(ImageProperties $properties): int
    {
        $response = $this->http->post('/render', $properties->toArray());

        return Arr::get($response, 'task');
    }

    protected function getImage(int $task): string
    {
        $response = $this->http->get('/image/stream/' . $task);

        if ($this->doesntSuccess($response)) {
            sleep(1);

            return $this->getImage($task);
        }

        return Arr::get($response, 'output.0.data');
    }

    protected function isSuccess(array $response): bool
    {
        return Arr::get($response, 'status') === 'succeeded';
    }

    protected function doesntSuccess(array $response): bool
    {
        return !$this->isSuccess($response);
    }
}
