<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Requests;

use DragonCode\Support\Concerns\Makeable;
use StableDiffusion\SamplersGenerator\Models\Models as NeuralModels;
use StableDiffusion\SamplersGenerator\Services\Http;

class Models
{
    use Makeable;

    public function __construct(
        protected Http $http = new Http()
    ) {
    }

    public function get(): NeuralModels
    {
        $response = $this->http->get('/get/models');

        return NeuralModels::make($response);
    }
}
