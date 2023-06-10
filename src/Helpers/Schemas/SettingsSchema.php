<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Helpers\Schemas;

use DragonCode\Support\Concerns\Makeable;
use StableDiffusionUI\SamplersGenerator\Helpers\Output;
use StableDiffusionUI\SamplersGenerator\Services\Validator;
use Throwable;

/**
 * @method static SettingsSchema make(Output $output)
 */
class SettingsSchema
{
    use Makeable;

    protected array $rules = [
        'seed' => ['required', 'int'],

        'reqBody'                 => ['required', 'array'],
        'reqBody.prompt'          => ['required', 'string'],
        'reqBody.negative_prompt' => ['required', 'string'],

        'reqBody.active_tags' => ['required', 'array'],

        'reqBody.guidance_scale'      => ['required', 'float', 'min:1'],
        'reqBody.use_face_correction' => ['bool'],
        'reqBody.output_format'       => ['required', 'string'],
    ];

    public function __construct(
        protected Output $output,
        protected Validator $validator = new Validator()
    ) {
        $this->validator->setOutput($this->output);
    }

    public function isValid(string $path): bool
    {
        return $this->isJson($path) && $this->validated($path);
    }

    protected function isJson(string $path): bool
    {
        try {
            $this->load($path);

            return true;
        }
        catch (Throwable) {
            return false;
        }
    }

    protected function validated(string $path): bool
    {
        return $this->validator->validated($this->load($path), $this->rules);
    }

    protected function load(string $path): array
    {
        return json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }
}
