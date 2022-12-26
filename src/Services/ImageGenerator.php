<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Services;

use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Str;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;
use StableDiffusion\SamplersGenerator\Services\Images\TextBlock;

class ImageGenerator
{
    public function __construct(
        protected Http $http = new Http()
    ) {
    }

    public function generate(ImageProperties $properties)
    {
        $values = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F'];
        $colorCallback = fn () => $values[array_rand($values)];

        $color = sprintf(
            '#%s%s%s%s%s%s',
            $colorCallback(),
            $colorCallback(),
            $colorCallback(),
            $colorCallback(),
            $colorCallback(),
            $colorCallback()
        );

        return TextBlock::make()->background($color)->text($color)->get();

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

        if (Arr::get($response, 'status') !== 'succeeded') {
            sleep(1);

            return $this->getImage($task);
        }

        return Arr::get($response, 'output.0.data');
    }
}
