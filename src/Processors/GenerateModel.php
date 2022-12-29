<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Processors;

use DragonCode\Support\Facades\Helpers\Arr;
use StableDiffusionUI\SamplersGenerator\Enums\VramLevel;
use StableDiffusionUI\SamplersGenerator\Models\ImageProperties;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateModel extends Processor
{
    protected array $collection = [];

    protected function run(): void
    {
        $samplers = $this->samplers();
        $steps    = $this->steps();
        $vae      = $this->vae();

        $this->eachVae($samplers, $steps, $vae);
    }

    protected function eachVae(array $samplers, array $steps, array $vaes): void
    {
        foreach ($vaes as $vae) {
            $this->forVae($samplers, $steps, $vae);
        }
    }

    protected function forVae(array $samplers, array $steps, string $vae): void
    {
        $bar = $this->progressBar(count($samplers), count($steps));

        $this->process($this->properties, $samplers, $steps, $vae, $this->vramLevel(), $bar);
        $this->store($samplers, $steps);
    }

    protected function process(ImageProperties $properties, array $samplers, array $steps, string $vae, VramLevel $vramLevel, ProgressBar $bar): void
    {
        foreach ($samplers as $sampler) {
            foreach ($steps as $step) {
                $properties->sampler           = $sampler;
                $properties->samplerName       = $sampler;
                $properties->numInferenceSteps = $step;
                $properties->useVaeModel       = $vae;
                $properties->vramUsageLevel    = $vramLevel->value;

                $this->collection[$sampler][$step] = $this->generate($properties);

                $bar->advance();
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

    protected function vramLevel(): VramLevel
    {
        return $this->config->get('vram.usage_level', VramLevel::BALANCED);
    }

    protected function progressBar(int ...$counts): ProgressBar
    {
        $bar = $this->output->createProgressBar(
            $this->progressBarSteps($counts)
        );

        $bar->setFormat(ProgressBar::FORMAT_VERY_VERBOSE);

        return $bar;
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
