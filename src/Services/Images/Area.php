<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Services\Images;

use Intervention\Image\Image;

class Area extends Base
{
    public function get(): Image
    {
        return $this->canvas();
    }
}
