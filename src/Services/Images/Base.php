<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Services\Images;

use Closure;
use DragonCode\Support\Concerns\Makeable;
use Intervention\Image\AbstractFont;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

abstract class Base
{
    use Makeable;

    protected int $cell = 512;

    protected int $columns = 1;

    protected int $rows = 1;

    protected ?string $background = null;

    protected string $font = __DIR__ . '/../../../resources/fonts/SourceCodePro-SemiBold.ttf';

    protected int $fontSize = 72;

    protected string $fontColor = '#292929';

    protected string $textAlign = 'center';

    protected string $textValign = 'center';

    protected ?string $text = null;

    public function __construct(
        protected ImageManager $image = new ImageManager()
    ) {
    }

    public function columns(int $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    public function rows(int $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    public function background(string $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function text(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    abstract public function get(): Image;

    protected function getX(): int
    {
        return (int)($this->cell * $this->columns / 2);
    }

    protected function getY(): int
    {
        return (int)($this->cell / 2);
    }

    public function canvas(): Image
    {
        return $this->image->canvas($this->getWidth(), $this->getHeight(), $this->background);
    }

    protected function getWidth(): int
    {
        return $this->columns * $this->cell;
    }

    protected function getHeight(): int
    {
        return $this->rows * $this->cell;
    }

    protected function font(): Closure
    {
        return fn (AbstractFont $font) => $font
            ->file($this->font)
            ->size($this->fontSize)
            ->color($this->fontColor)
            ->align($this->textAlign)
            ->valign($this->textValign);
    }
}
