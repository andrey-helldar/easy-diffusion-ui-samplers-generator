<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Processors;

use DragonCode\Support\Facades\Helpers\Arr;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;

class GenerateModel extends Processor
{
    protected array $collection = [];

    public function handle(): void
    {
        $samplers = $this->samplers();
        $steps = $this->steps();

        $this->output->task('Generating', fn () => $this->process($this->properties, $samplers, $steps));
        $this->output->task('Storing', fn () => $this->store($samplers, $steps));
    }

    protected function process(ImageProperties $properties, array $samplers, array $steps): void
    {
        foreach ($this->vae() as $vae) {
            foreach ($samplers as $sampler) {
                foreach ($steps as $step) {
                    $properties->sampler = $sampler;
                    $properties->numInferenceSteps = $step;
                    $properties->useVaeModel = $vae;

                    $this->collection[$sampler][$step] = $this->generate($properties);
                }
            }
        }
    }

    protected function generate(ImageProperties $properties)
    {
        return $this->image->generate($properties);
    }

    protected function store(array $samplers, array $steps): void
    {
        $this->filesystem->store($this->properties, $samplers, $steps, $this->collection);
    }

    protected function vae(): array
    {
        return $this->models()->vae;
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
