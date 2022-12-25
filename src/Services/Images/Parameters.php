<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Services\Images;

use Intervention\Image\Image;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;

class Parameters extends Base
{
    protected ?ImageProperties $properties = null;
    
    protected int $fontSize = 36;

    public function properties(ImageProperties $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    public function get(): Image
    {
        return $this->canvas()->text($this->getContent(), $this->getX(), $this->getY(), $this->font());
    }

    protected function getContent(): string
    {
        $result = '';

        foreach ($this->properties->toArray() as $key => $value) {
            if (in_array($key, [
                'height',
                'width',
                'num_outputs',
                'original_prompt',
                'output_format',
                'output_quality',
                'save_to_disk_path',
                'session_id',
                'show_only_filtered_image',
                'stream_image_progress',
                'stream_progress_updates',
                'turbo',
                'use_full_precision'
            ])) {
                continue;
            }

            $result .= $key . ': ' . (is_array($value) ? implode(', ', $value) : $value) . PHP_EOL;
        }

        return $result;
    }

    protected function getHeight(): int
    {
        return (int)(parent::getHeight() / 4);
    }

    protected function getY(): int
    {
        return (int)(parent::getY() / 4);
    }
}
