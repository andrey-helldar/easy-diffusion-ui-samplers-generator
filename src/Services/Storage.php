<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services;

use Closure;
use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Filesystem\File;
use DragonCode\Support\Facades\Helpers\Arr;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use StableDiffusionUI\SamplersGenerator\Concerns\HasSizes;
use StableDiffusionUI\SamplersGenerator\Models\ImageProperties;
use StableDiffusionUI\SamplersGenerator\Services\Images\Area;
use StableDiffusionUI\SamplersGenerator\Services\Images\FromBase64;
use StableDiffusionUI\SamplersGenerator\Services\Images\Header;
use StableDiffusionUI\SamplersGenerator\Services\Images\Parameters;
use StableDiffusionUI\SamplersGenerator\Services\Images\TextBlock;

class Storage
{
    use HasSizes;

    protected string $font = __DIR__ . '/../../resources/fonts/SourceCodePro-SemiBold.ttf';

    public function __construct(
        protected Config       $config = new Config(),
        protected ImageManager $image = new ImageManager()
    ) {
    }

    public function store(
        ImageProperties $properties,
        array           $samplers,
        array           $steps,
        array           $collection
    ): void {
        $image = $this->canvas(count($samplers) + 1, count($steps) + 2);

        $this->setHeaders($image, $properties, $samplers, $steps);
        $this->process($image, $collection);

        $this->storeImage($image, $this->getPath($properties, $properties->outputFormat));
        $this->storeParameters($properties, $this->getPath($properties, 'json'));
    }

    protected function storeImage(Image $image, string $path): void
    {
        $image->save($path);
    }

    protected function storeParameters(ImageProperties $properties, string $path): void
    {
        File::store($path, $properties->toConfigFile());
    }

    protected function process(Image &$image, array $collection): void
    {
        $collection = Arr::ksort($collection);

        foreach (array_values($collection) as $column => $items) {
            foreach (array_values($items) as $row => $data) {
                $this->insert($image, $column + 1, $row + 2, $this->resolveImage($data));
            }
        }
    }

    protected function insert(Image &$area, int $left, float $top, Image $data): void
    {
        $area->insert($data, 'top-left', $left * $this->getCellSize(), (int) ($top * $this->getCellSize()));
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

        $this->insert($image, 0, 0.5, $data);
    }

    protected function setSamplers(Image &$image, array $samplers): void
    {
        $this->setHeader($image, $samplers, fn (int $index) => $index + 1, fn () => 1);
    }

    protected function setSteps(Image &$image, array $steps): void
    {
        $this->setHeader($image, $steps, fn ($i) => 0, fn (int $index) => $index + 2, 'Step');
    }

    protected function setHeader(Image &$image, array $items, Closure $left, Closure $top, ?string $prefix = null): void
    {
        foreach (array_values($items) as $index => $value) {
            $data = $this->textBlock(
                ltrim($prefix . ': ' . $value, ': ')
            );

            $this->insert($image, $left($index), $top($index), $data);
        }
    }

    protected function resolveImage(Image|string $content): Image
    {
        return FromBase64::make()->content($content)->get();
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

    protected function getPath(ImageProperties $properties, string $extension): string
    {
        return $this->getDirectory($properties) . '/' . $properties->useStableDiffusionModel . '__' . $properties->getVaeModel() . '.' . $extension;
    }

    protected function getDirectory(ImageProperties $properties): string
    {
        return $this->ensureDirectory(
            implode('/', [$properties->path, $properties->getInitiatedAt(), $properties->configName])
        );
    }

    protected function ensureDirectory(string $directory): string
    {
        Directory::ensureDirectory($directory);

        return $directory;
    }
}
