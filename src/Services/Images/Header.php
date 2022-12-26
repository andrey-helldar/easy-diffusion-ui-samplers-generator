<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services\Images;

use Intervention\Image\Image;

class Header extends Base
{
    public function get(): Image
    {
        return $this->canvas()->text($this->text, $this->getX(), $this->getY(), $this->font());
    }

    protected function getHeight(): int
    {
        return (int) (parent::getHeight() / 2);
    }

    protected function getY(): int
    {
        return (int) (parent::getY() / 2);
    }
}
