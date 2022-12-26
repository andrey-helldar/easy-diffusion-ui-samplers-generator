<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services\Images;

use Intervention\Image\Image;

class FromBase64 extends Base
{
    protected Image|string|null $content;

    public function content(Image|string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function get(): Image
    {
        return $this->image->make($this->content);
    }
}
