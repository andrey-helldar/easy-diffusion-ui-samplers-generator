<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Concerns;

use StableDiffusionUI\SamplersGenerator\Enums\Size;

trait HasSizes
{
    protected ?int $cellSize = null;

    protected function getCellSize(): int
    {
        if (! is_null($this->cellSize)) {
            return $this->cellSize;
        }

        return $this->cellSize = $this->config->get('sizes.cell', Size::MEDIUM)->value;
    }
}
