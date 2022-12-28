<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Processors;

use DragonCode\Support\Facades\Helpers\Arr;
use StableDiffusionUI\SamplersGenerator\Models\ImageProperties;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateModel extends Processor
{
    protected array $collection = [];

    protected function run(): void
    {
        $samplers = $this->samplers();
        $steps    = $this->steps();

        $bar = $this->progressBar(count($samplers), count($steps), count($this->vae()));

        $this->process($this->properties, $samplers, $steps, $bar);
        $this->store($samplers, $steps);
    }

    protected function process(ImageProperties $properties, array $samplers, array $steps, ProgressBar $bar): void
    {
        foreach ($this->vae() as $vae) {
            foreach ($samplers as $sampler) {
                foreach ($steps as $step) {
                    $properties->sampler           = $sampler;
                    $properties->samplerName       = $sampler;
                    $properties->numInferenceSteps = $step;
                    $properties->useVaeModel       = $vae;

                    $this->collection[$sampler][$step] = $this->generate($properties);

                    $bar->advance();
                }
            }
        }

        $bar->finish();
        $this->output->emptyLine(2);
    }

    protected function generate(ImageProperties $properties): string
    {
        return $this->image->generate($properties);
    }

    protected function store(array $samplers, array $steps): void
    {
        $this->output->task(
            'Storing',
            fn () => $this->filesystem->store($this->properties, $samplers, $steps, $this->collection)
        );
    }

    protected function vae(): array
    {
        return Arr::of($this->models()->vae)
            ->filter()
            ->unique()
            ->push('')
            ->toArray();
    }

    protected function steps(): array
    {
        return Arr::sort($this->config->get('steps'));
    }

    protected function progressBar(int ...$counts): ProgressBar
    {
        return $this->output->createProgressBar(
            $this->progressBarSteps($counts)
        );
    }

    protected function progressBarSteps(array $counts): int
    {
        $result = 1;

        foreach ($counts as $count) {
            $result *= $count;
        }

        return $result;
    }
}
