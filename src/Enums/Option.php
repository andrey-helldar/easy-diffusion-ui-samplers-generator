<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string FIX_FACES()
 * @method static string MODEL()
 * @method static string MODIFIERS()
 * @method static string NEGATIVE_PROMPT()
 * @method static string PATH()
 * @method static string PROMPT()
 * @method static string SEED()
 */
enum Option: string
{
    use InvokableCases;

    case FIX_FACES = 'fix-faces';
    case MODEL = 'model';
    case MODIFIERS = 'modifiers';
    case NEGATIVE_PROMPT = 'negative-prompt';
    case PATH = 'path';
    case PROMPT = 'prompt';
    case SEED = 'seed';
}
