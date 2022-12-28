<?php

declare(strict_types=1);

namespace StableDiffusionUI\SamplersGenerator\Services;

use DragonCode\Support\Concerns\Makeable;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @method static Parser make(string $path)
 */
class Parser
{
    use Makeable;

    public function __construct(
        protected string $filename
    ) {
    }

    public function getSelect(string $elementId): array
    {
        return $this->crawler()
            ->evaluate("//select[@id=\"$elementId\"]/option")
            ->extract(['value']);
    }

    protected function crawler(): Crawler
    {
        return new Crawler(file_get_contents($this->filename));
    }
}
