<?php

declare(strict_types=1);

namespace CommandValidator;

use CommandValidator\Actions\ValidateCommand;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

final class CommandValidatorTestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(HuntDevilCommand::class);
        }
        Event::listen(CommandStarting::class, function (CommandStarting $event): void {
            $command = Artisan::all()[$event->command] ?? null;

            if ($command && method_exists($command, 'options')) {
                $command->setInput($event->input);
                $command->setOutput(new OutputStyle(
                    $event->input,
                    $event->output
                ));
                app(ValidateCommand::class)->handle($command);
            }
        });
    }
}
