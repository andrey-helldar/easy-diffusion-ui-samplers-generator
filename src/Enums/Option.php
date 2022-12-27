<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string FIX_FACES()
 * @method static string MODEL()
 * @method static string NEGATIVE_PROMPT()
 * @method static string OUTPUT_FORMAT()
 * @method static string OUTPUT_QUALITY()
 * @method static string PATH()
 * @method static string PROMPT()
 * @method static string SEED()
 * @method static string SHOW_PATH()
 * @method static string TAGS()
 */
enum Option: string
{
    use InvokableCases;

    case FIX_FACES = 'fix-faces';
    case MODEL = 'model';
    case NEGATIVE_PROMPT = 'negative-prompt';
    case OUTPUT_FORMAT = 'output-format';
    case OUTPUT_QUALITY = 'output-quality';
    case PATH = 'path';
    case PROMPT = 'prompt';
    case SEED = 'seed';
    case SHOW_PATH = 'show-path';
    case TAGS = 'tags';
}
