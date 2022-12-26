<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services\Images;

use Closure;
use DragonCode\Support\Concerns\Makeable;
use Intervention\Image\AbstractFont;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use StableDiffusionUI\SamplersGenerator\Concerns\HasSizes;
use StableDiffusionUI\SamplersGenerator\Services\Config;

abstract class Base
{
    use HasSizes;
    use Makeable;

    protected int $columns = 1;

    protected int $rows = 1;

    protected ?string $background = null;

    protected string $font = __DIR__ . '/../../../resources/fonts/SourceCodePro-SemiBold.ttf';

    protected string $fontColor = '#292929';

    protected string $textAlign = 'center';

    protected string $textValign = 'center';

    protected ?string $text = null;

    abstract public function get(): Image;

    public function __construct(
        protected Config $config = new Config(),
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

    protected function getX(): int
    {
        return (int) ($this->getCellSize() * $this->columns / 2);
    }

    protected function getY(): int
    {
        return (int) ($this->getCellSize() / 2);
    }

    public function canvas(): Image
    {
        return $this->image->canvas($this->getWidth(), $this->getHeight(), $this->background);
    }

    protected function getWidth(): int
    {
        return $this->columns * $this->getCellSize();
    }

    protected function getHeight(): int
    {
        return $this->rows * $this->getCellSize();
    }

    protected function getFontSize(): int
    {
        return $this->config->get('sizes.font.header', 72);
    }

    protected function font(): Closure
    {
        return fn (AbstractFont $font) => $font
            ->file($this->font)
            ->size($this->getFontSize())
            ->color($this->fontColor)
            ->align($this->textAlign)
            ->valign($this->textValign);
    }
}
