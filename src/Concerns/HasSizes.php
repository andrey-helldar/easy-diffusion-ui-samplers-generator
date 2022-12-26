<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Concerns;

trait HasSizes
{
    protected ?int $cellSize = null;

    protected function getCellSize(): int
    {
        if (! is_null($this->cellSize)) {
            return $this->cellSize;
        }

        return $this->cellSize = (int) $this->config->get('sizes.cell', 256);
    }
}
