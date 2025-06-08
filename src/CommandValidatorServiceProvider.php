<?php

declare(strict_types=1);

namespace CommandValidator;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;

final class CommandValidatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->beforeResolving(function ($abstract): void {
            $class = is_string($abstract) ? $abstract : $abstract::class;

            if (! class_exists($class)) {
                return;
            }

            if (! is_subclass_of($class, Command::class)) {
            }
        });
    }
}
