<?php

declare(strict_types=1);

namespace CommandValidator\Actions;

use CommandValidator\Attributes\Validatable;
use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

final class ValidateCommand
{
    public function handle(Command $command): void
    {
        $reflection = new ReflectionClass($command);

        $attribute = $reflection->getAttributes(Validatable::class)[0] ?? null;

        if (! $attribute) {
            return;
        }

        $rules = $attribute->newInstance()->rules;

        $validator = Validator::make([
            ...array_filter($command->options()),
            ...array_filter($command->arguments())],
            $rules
        );

        /** @var Application $app */
        $app = app(Application::class);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $command->error($error);
            }
            if ($app->runningUnitTests()) {
                throw new ValidationException($validator);
            }
            exit(1);
        }
    }
}
