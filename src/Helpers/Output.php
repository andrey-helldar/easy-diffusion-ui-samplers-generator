<?php

declare(strict_types=1);

namespace StableDiffusion\SamplersGenerator\Helpers;

use Carbon\Carbon;
use DragonCode\Support\Concerns\Makeable;
use DragonCode\Support\Facades\Instances\Call;
use Illuminate\Console\OutputStyle;
use Illuminate\Console\View\Components\Factory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @method static Output make(InputInterface $input, SymfonyStyle $output)
 */
class Output
{
    use Makeable;

    /** @var Factory */
    protected mixed $components = null;

    public function __construct(
        protected InputInterface $input,
        protected SymfonyStyle $output
    ) {
    }

    public function info(string $message): void
    {
        $this->when(
            $this->hasComponent(),
            fn () => $this->component()->info($message),
            fn () => $this->output->info($message)
        );
    }

    public function warn(string $message): void
    {
        $this->when(
            $this->hasComponent(),
            fn () => $this->component()->warn($message),
            fn () => $this->output->warning($message)
        );
    }

    public function line(string $message): void
    {
        $this->output->writeln($message);
    }

    public function twoColumnDetail(string $first, string $second): void
    {
        $this->when(
            $this->hasComponent(),
            fn () => $this->component()->twoColumnDetail($first, $second),
            fn () => $this->line($first . ': ' . $second)
        );
    }

    public function task(string $message, callable $callback): void
    {
        $this->when(
            $this->hasComponent(),
            fn () => $this->component()->task($message, $callback),
            fn () => $this->simpleTask($message, $callback)
        );
    }

    public function timed(callable ...$callbacks): void
    {
        $startAt = $this->now();

        foreach ($callbacks as $callback) {
            $callback();
        }

        $this->twoColumnDetail('Elapsed Time', $this->now()->longAbsoluteDiffForHumans($startAt, 2));
    }

    public function emptyLine(int $lines = 1): void
    {
        for ($i = 0; $i < $lines; $i++) {
            $this->output->writeln('');
        }
    }

    public function createProgressBar(int $max): ProgressBar
    {
        return $this->output->createProgressBar($max);
    }

    protected function when(bool $when, callable $callback, ?callable $fallback = null): void
    {
        if ($when) {
            Call::callback($callback);

            return;
        }

        if (!empty($fallback)) {
            Call::callback($fallback);
        }
    }

    protected function simpleTask(string $message, callable $callback): void
    {
        $this->output->writeln($message);

        Call::callback($callback);
    }

    protected function hasComponent(): bool
    {
        return class_exists(Factory::class);
    }

    protected function component(): Factory
    {
        if (!empty($this->components)) {
            return $this->components;
        }

        return $this->components = new Factory($this->illuminateOutput());
    }

    protected function illuminateOutput(): OutputStyle
    {
        return new OutputStyle($this->input, $this->output);
    }

    protected function now(): Carbon
    {
        return Carbon::now();
    }
}
