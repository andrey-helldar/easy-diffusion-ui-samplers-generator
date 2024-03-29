<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Models;

use Carbon\Carbon;
use DragonCode\SimpleDataTransferObject\DataTransferObject;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Boolean;
use DragonCode\Support\Facades\Helpers\Str;

/**
 * @method ImageProperties fromArray(array $items)
 */
class ImageProperties extends DataTransferObject
{
    public array $activeTags = [];

    public float $guidanceScale = 7.5;

    public int $height = 512;

    public string $negativePrompt = '';

    public int $numInferenceSteps = 20;

    public int $numOutputs = 1;

    public ?string $originalPrompt = null;

    public string $outputFormat = 'jpeg';

    public int $outputQuality = 75;

    public ?string $prompt = null;

    public string $sampler = 'euler_a';

    public string $samplerName = 'euler_a';

    public string $saveToDiskPath = '';

    public ?int $seed = null;

    public ?int $sessionId = null;

    public bool $showOnlyFilteredImage = true;

    public bool $streamImageProgress = false;

    public bool $streamProgressUpdates = true;

    public bool $turbo = true;

    public bool $useFullPrecision = false;

    public ?string $useStableDiffusionModel = null;

    public string $useVaeModel = '';

    public bool $useFaceCorrection = false;

    public int $width = 512;

    public string $path = '';

    public ?string $configName = null;

    public bool $showPathInfo = true;

    public ?string $device = null;

    public bool $singleModel = false;

    public ?string $vramUsageLevel = null;

    protected $map = [
        'reqBody.prompt'                     => 'originalPrompt',
        'reqBody.negative_prompt'            => 'negativePrompt',
        'reqBody.active_tags'                => 'activeTags',
        'reqBody.guidance_scale'             => 'guidanceScale',
        'reqBody.use_face_correction'        => 'useFaceCorrection',
        'reqBody.output_format'              => 'outputFormat',
        'reqBody.output_quality'             => 'outputQuality',
        'reqBody.use_stable_diffusion_model' => 'useStableDiffusionModel',
        'reqBody.use_vae_model'              => 'useVaeModel',
        'reqBody.vram_usage_level'           => 'vramUsageLevel',
    ];

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
        return Arr::of([
            'prompt'          => $this->originalPrompt,
            'negative_prompt' => $this->negativePrompt,
            'tags'            => $this->activeTags,
            'model'           => $this->useStableDiffusionModel,
            'vae'             => $this->getVaeModel(),
            'guidance_scale'  => $this->guidanceScale,
            'face_correction' => $this->getFaceCorrection(),
            'seed'            => $this->seed,
            'device'          => $this->device,
            'generated_at'    => $this->getDate(),
        ])
            ->renameKeys(fn (string $key) => Str::of($key)->title()->replace('_', ' ')->toString())
            ->toArray();
    }

    public function toConfigFile(): string
    {
        return json_encode([
            'numOutputsTotal' => 1,
            'seed'            => $this->seed,

            'reqBody' => [
                'prompt'                     => $this->originalPrompt,
                'negative_prompt'            => $this->negativePrompt,
                'active_tags'                => $this->activeTags,
                'width'                      => $this->width,
                'height'                     => $this->height,
                'seed'                       => $this->seed,
                'num_inference_steps'        => $this->numInferenceSteps,
                'guidance_scale'             => $this->guidanceScale,
                'use_face_correction'        => $this->useFaceCorrection,
                'sampler'                    => $this->samplerName,
                'use_stable_diffusion_model' => $this->useStableDiffusionModel,
                'use_vae_model'              => $this->useVaeModel,
                'use_hypernetwork_model'     => '',
                'hypernetwork_strength'      => 1,
                'num_outputs'                => $this->numOutputs,
                'stream_image_progress'      => false,
                'show_only_filtered_image'   => true,
                'output_format'              => $this->outputFormat,
                'output_quality'             => $this->outputQuality,
                'vram_usage_level'           => $this->vramUsageLevel,
            ],
        ], JSON_UNESCAPED_UNICODE ^ JSON_PRETTY_PRINT);
    }

    public function getInitiatedAt(): string
    {
        return Carbon::createFromTimestamp($this->sessionId)->format('Y-m-d_H-i-s');
    }

    protected function resolve(): void
    {
        $this->resolvePrompt();
    }

    protected function resolvePrompt(): void
    {
        $tags = implode(', ', $this->activeTags);

        $this->prompt = trim($this->originalPrompt . ', ' . $tags, ', ');
    }

    protected function castPrompt(string $value): string
    {
        return $this->cleanString($value);
    }

    protected function castActiveTags(array $values): array
    {
        return Arr::of($values)
            ->filter()
            ->map(fn (string $value) => $this->cleanString($value))
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }

    protected function castUseFaceCorrection(mixed $value): bool
    {
        return Boolean::to($value);
    }

    protected function castSeed(mixed $value): ?int
    {
        return empty($value) ? null : (int) $value;
    }

    protected function castOutputFormat(string $value): string
    {
        return Str::lower($value) === 'png' ? 'png' : 'jpeg';
    }

    protected function castOutputQuality(mixed $value): int
    {
        return min(max((int) $value, 1), 100);
    }

    protected function castSingleModel(mixed $value): bool
    {
        return Boolean::to($value);
    }

    public function setSessionId(?int $timestamp = null): self
    {
        $this->sessionId = $timestamp ?: time();

        return $this;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function setConfigName(string $name): self
    {
        $this->configName = $name;

        return $this;
    }

    public function setDevice(string $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function setOutput(string $extension, int $quality): self
    {
        $this->outputFormat  = $this->castOutputFormat($extension);
        $this->outputQuality = $this->castOutputQuality($quality);

        return $this;
    }

    public function setSize(int $size): self
    {
        $this->width  = $size;
        $this->height = $size;

        return $this;
    }

    public function setSingleModel(bool $single): self
    {
        $this->singleModel = $single;

        return $this;
    }

    public function resetNumOutputs(): self
    {
        $this->numOutputs = 1;

        return $this;
    }

    public function resetShowOnlyFilteredImage(): self
    {
        $this->showOnlyFilteredImage = true;

        return $this;
    }

    public function resetStreamImageProgress(): self
    {
        $this->streamImageProgress = false;

        return $this;
    }

    public function resetStreamProgressUpdates(): self
    {
        $this->streamProgressUpdates = true;

        return $this;
    }

    public function getVaeModel(): string
    {
        return $this->useVaeModel ?: 'None';
    }

    protected function getFaceCorrection(): bool|string
    {
        return $this->useFaceCorrection ? 'GFPGANv1.3' : false;
    }

    protected function getDate(): string
    {
        return date('Y-m-d, H:i');
    }

    protected function cleanString(string $value): string
    {
        return Str::of($value)->squish()->trim()->toString();
    }
}
