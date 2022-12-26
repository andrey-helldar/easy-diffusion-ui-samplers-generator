<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Processors;

class GenerateModels extends Processor
{
    protected function run(): void
    {
        $items = $this->models();

        $this->each($items->models);
    }

    protected function each(array $models): void
    {
        foreach ($models as $model) {
            $this->info($model);
            $this->process($model);
            $this->output->emptyLine();
        }
    }

    protected function process(string $model): void
    {
        $this->properties->useStableDiffusionModel = $model;
        $this->properties->showPathInfo = false;

        $this->resolveProcessor(GenerateModel::class, $this->properties)->handle();
    }

    protected function info(string $model): void
    {
        $this->output->info($model);
    }
}
