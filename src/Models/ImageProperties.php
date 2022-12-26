<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Models;

use DragonCode\SimpleDataTransferObject\DataTransferObject;
use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Str;

class ImageProperties extends DataTransferObject
{
    public array $activeTags = [];

    public float $guidanceScale = 7.5;

    public int $height = 512;

    public string $negativePrompt = '';

    public int $numInferenceSteps = 20;

    public int $numOutputs = 1;

    public ?string $originalPrompt = null;

    public string $outputFormat = 'png';

    public int $outputQuality = 85;

    public ?string $prompt = null;

    public string $sampler = 'plms';

    public string $saveToDiskPath = '';

    public ?int $seed = null;

    public ?int $sessionId = null;

    public bool $showOnlyFilteredImage = true;

    public bool $streamImageProgress = false;

    public bool $streamProgressUpdates = true;

    public bool $turbo = true;

    public bool $useFullPrecision = true;

    public ?string $useStableDiffusionModel = null;

    public ?string $useVaeModel = null;

    public bool $useFaceCorrection = false;

    public int $width = 512;

    public string $path = '';

    public bool $showPathInfo = true;

    public ?string $device = null;

    public function __construct(array $items = [])
    {
        parent::__construct($items);

        $this->setSessionId();
    }

    public function toArray(): array
    {
        $this->resolve();

        return Arr::renameKeys(parent::toArray(), fn (string $key) => Str::snake($key));
    }

    public function toImage(): array
    {
        return [
            'prompt' => $this->prompt,
            'negative_prompt' => $this->negativePrompt,
            'modifiers' => $this->activeTags,
            'model' => $this->useStableDiffusionModel,
            'vae' => $this->useVaeModel,
            'sampler' => $this->sampler,
            'steps' => $this->numInferenceSteps,
            'guidance_scale' => $this->guidanceScale,
            'face_correction' => $this->getFaceCorrection(),
            'device' => $this->device,
            'date' => $this->getDate(),
        ];
    }

    protected function resolve(): void
    {
        $this->resolveOriginalPrompt();
    }

    protected function resolveOriginalPrompt(): void
    {
        $this->originalPrompt = $this->prompt;
    }

    protected function castPrompt(string $value): string
    {
        return trim($value);
    }

    protected function castPath(string $value): string
    {
        Directory::ensureDirectory($value);

        return realpath($value);
    }

    protected function castActiveTags(array $values): array
    {
        return Arr::of($values)
            ->filter()
            ->map(fn (string $value) => trim($value))
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    protected function castSeed(mixed $value): ?int
    {
        return empty($value) ? null : (int)$value;
    }

    protected function setSessionId(): void
    {
        $this->sessionId = time();
    }

    protected function getFaceCorrection(): string | bool
    {
        return $this->useFaceCorrection ? 'GFPGANv1.3' : false;
    }

    protected function getDate(): string
    {
        return date('Y-m-d, H:i');
    }
}
