<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Processors;

use DragonCode\Contracts\DataTransferObject\DataTransferObject;
use DragonCode\Support\Facades\Helpers\Arr;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;
use StableDiffusion\SamplersGenerator\Models\Models;
use StableDiffusion\SamplersGenerator\Requests\Models as ModelsRequest;

class Generator extends Processor
{
    public function handle(): void
    {
        $models = $this->models();
        $properties = $this->properties();

        $this->each($properties, $models->models, $models->vae, $this->samplers(), $this->steps());
    }

    protected function each(
        ImageProperties $properties,
        array $models,
        array $vaes,
        array $samplers,
        array $steps
    ): void {
        foreach ($models as $model) {
            foreach ($vaes as $vae) {
                $properties->useStableDiffusionModel = $model;
                $properties->useVaeModel = $vae;

                $collection = [];

                foreach ($samplers as $sampler) {
                    foreach ($steps as $step) {
                        $properties->sampler = $sampler;
                        $properties->numInferenceSteps = $step;

                        $collection[$sampler][$step] = $this->generate($properties);
                    }
                }

                $this->store($properties, $samplers, $steps, $collection);

                dd('finished');
            }
        }
    }

    protected function generate(ImageProperties $properties)
    {
        return $this->image->generate($properties);
    }

    protected function store(ImageProperties $properties, array $samplers, array $steps, array $collection): void
    {
        $this->filesystem->store($this->path, $properties, $samplers, $steps, $collection);
    }

    protected function properties(): DataTransferObject | ImageProperties
    {
        return ImageProperties::fromArray([
            'prompt' => $this->prompt,
            'negativePrompt' => $this->negativePrompt,
            'activeTags' => $this->modifiers,
            'seed' => $this->seed,
        ]);
    }

    protected function models(): Models
    {
        return ModelsRequest::make()->get();
    }

    protected function samplers(): array
    {
        return Arr::sort($this->config->get('samplers'));
    }

    protected function steps(): array
    {
        return Arr::sort($this->config->get('steps'));
    }
}
