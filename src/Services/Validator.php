<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services;

use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Helpers\Boolean;
use DragonCode\Support\Facades\Helpers\Str;
use StableDiffusionUI\SamplersGenerator\Helpers\Output;

class Validator
{
    protected ?Output $output = null;

    public function setOutput(Output $output): self
    {
        $this->output = $output;

        return $this;
    }

    public function validated(array $data, array $rules): bool
    {
        foreach ($rules as $key => $rls) {
            foreach ($rls as $rule) {
                $value = Arr::get($data, $key);
                $param = Str::after($rule, ':');
                $rule  = Str::before($rule, ':');

                if (! $this->assert($data, $key, $value, $rule, $param)) {
                    $this->message($key, $value, $rule);

                    return false;
                }
            }
        }

        return true;
    }

    protected function assert(array $items, string $key, mixed $value, string $rule, mixed $param): bool
    {
        return match ($rule) {
            'required' => $this->validateRequired($items, $key),
            'int'      => $this->validateInteger($value),
            'array'    => $this->validateArray($value),
            'string'   => $this->validateString($value),
            'float'    => $this->validateFloat($value),
            'min'      => $this->validateMin($value, (int) $param),
            'bool'     => $this->validateBoolean($value),
            default    => false
        };
    }

    protected function message(string $key, mixed $value, string $rule): void
    {
        $value = is_array($value) ? implode('", "', $value) : $value;

        $this->output->warn("The \"$key\" doesn't satisfy the \"$rule\" rule. Value is \"$value\"");
    }

    protected function validateRequired(array $items, string $key): bool
    {
        return Arr::exists($items, $key);
    }

    protected function validateInteger(mixed $value): bool
    {
        return is_integer($value);
    }

    protected function validateFloat(mixed $value): bool
    {
        return is_float($value);
    }

    protected function validateNumeric(mixed $value): bool
    {
        return is_numeric($value);
    }

    protected function validateArray(mixed $value): bool
    {
        return is_array($value);
    }

    protected function validateString(mixed $value): bool
    {
        return is_string($value);
    }

    protected function validateBoolean(mixed $value): bool
    {
        return Boolean::parse($value) !== null;
    }

    protected function validateMin(mixed $value, int $param): bool
    {
        return match (true) {
            $this->validateNumeric($value) => $value >= $param,
            $this->validateArray($value)   => $param <= count($value),
            default                        => $param <= Str::length($value)
        };
    }
}
