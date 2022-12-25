<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Services;

use DragonCode\Support\Facades\Helpers\Arr;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use StableDiffusion\SamplersGenerator\Models\ImageProperties;
use StableDiffusion\SamplersGenerator\Services\Images\Area;
use StableDiffusion\SamplersGenerator\Services\Images\FromBase64;
use StableDiffusion\SamplersGenerator\Services\Images\Header;
use StableDiffusion\SamplersGenerator\Services\Images\Parameters;
use StableDiffusion\SamplersGenerator\Services\Images\TextBlock;

class Filesystem
{
    protected int $cell = 512;

    protected string $font = __DIR__ . '/../../resources/fonts/SourceCodePro-SemiBold.ttf';

    public function __construct(
        protected ImageManager $image = new ImageManager()
    ) {
    }

    public function store(
        string $path,
        ImageProperties $properties,
        array $samplers,
        array $steps,
        array $collection
    ): void {
        $image = $this->canvas(count($samplers) + 1, count($steps) + 2);

        $this->setHeaders($image, $properties, $samplers, $steps);

        $collection = Arr::ksort($collection);

        foreach (array_values($collection) as $column => $items) {
            foreach (array_values($items) as $row => $data) {
                $cell = FromBase64::make()->content($data)->get();

                $this->insert($image, $column + 1, $row + 2, $cell);
            }
        }

        $image->save($this->getPath($path, $properties));

        dd('aaa');
    }

    protected function insert(Image &$area, int $left, int $top, Image $data): void
    {
        $area->insert($data, 'top-left', $left * $this->cell, $top * $this->cell);
    }

    protected function setHeaders(Image &$image, ImageProperties $properties, array $samplers, array $steps): void
    {
        $this->setModelName($image, $properties, count($samplers) + 1);
        $this->setVae($image, $properties, count($samplers) + 1);

        $this->setSamplers($image, $samplers);
        $this->setSteps($image, $steps);
    }

    protected function setModelName(Image &$image, ImageProperties $properties, int $columns): void
    {
        $data = Header::make()->columns($columns)->text('Model: ' . $properties->useStableDiffusionModel)->get();

        $this->insert($image, 0, 0, $data);
    }

    protected function setVae(Image &$image, ImageProperties $properties, int $columns): void
    {
        $data = Parameters::make()->columns($columns)->properties($properties)->get();

        $this->insert($image, 0, 1, $data);
    }

    protected function setSamplers(Image &$image, array $samplers): void
    {
        foreach ($samplers as $index => $sampler) {
            $data = $this->textBlock($sampler);

            $this->insert($image, $index + 1, 1, $data);
        }
    }

    protected function setSteps(Image &$image, array $steps): void
    {
        foreach ($steps as $index => $step) {
            $data = $this->textBlock('Step: ' . $step);

            $this->insert($image, 0, $index + 2, $data);
        }
    }

    protected function textBlock(string $text): Image
    {
        return TextBlock::make()->text($text)->get();
    }

    protected function canvas(int $columns, int $rows): Image
    {
        return Area::make()
            ->background('#FFFFFF')
            ->columns($columns)
            ->rows($rows)
            ->get();
    }

    protected function getPath(string $directory, ImageProperties $properties): string
    {
        return $directory . '/' . $properties->useStableDiffusionModel . '__' . $properties->useVaeModel . '.png';
    }
}
