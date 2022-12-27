<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Processors;

class GenerateModels extends Processor
{
    protected function run(): void
    {
        $this->each($this->getModels());
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
        $this->properties->showPathInfo            = false;

        $this->resolveProcessor(GenerateModel::class, $this->properties)->handle();
    }

    protected function info(string $model): void
    {
        $this->output->info($model);
    }

    protected function getModels(): array
    {
        if ($this->properties->singleModel) {
            return [$this->properties->useStableDiffusionModel];
        }

        return $this->models()->models;
    }
}
