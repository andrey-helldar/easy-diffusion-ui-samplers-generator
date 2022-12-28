<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services\Images;

use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Boolean;
use Intervention\Image\Image;
use StableDiffusionUI\SamplersGenerator\Models\ImageProperties;

class Parameters extends Base
{
    protected ?ImageProperties $properties = null;

    protected int $fontSize = 28;

    protected string $textAlign = 'left';

    protected string $textValign = 'top';

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
        $parameters = $this->properties->toImage();

        $length = Arr::longestStringLength(array_keys($parameters));

        return Arr::of($parameters)
            ->map(fn (mixed $value, string $key) => str_pad($key . ': ', $length + 2, '.') . ' ' . $this->resolveValue($value))
            ->implode(PHP_EOL)
            ->toString();
    }

    protected function resolveValue(mixed $value): string
    {
        return match (gettype($value)) {
            'array'   => implode(', ', $value),
            'boolean' => Boolean::toString($value),
            default   => (string) $value
        };
    }

    protected function getX(): int
    {
        return 50;
    }

    protected function getY(): int
    {
        return 50;
    }
}
