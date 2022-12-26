<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services\Images;

use Intervention\Image\Image;

class TextBlock extends Base
{
    public function get(): Image
    {
        return $this->canvas()->text($this->text, $this->getX(), $this->getY(), $this->font());
    }
}
