<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;

error_reporting(E_ALL);
ini_set('display_errors', '1');

function runValidatedCommand(string $commandName, array $inputArgs = []): int
{
    return Artisan::call($commandName, $inputArgs);
}

function assertValidationErrors(array $input, array $expectedErrors): void
{
    try {
        runValidatedCommand('devil:hunt', $input);
        test()->fail('Expected ValidationException was not thrown.');
    } catch (ValidationException $e) {
        expect($e->errors())->toMatchArray($expectedErrors);
    }
}

it('throws Symfony exception when required argument is missing', function (): void {
    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Not enough arguments (missing: "kills").');

    runValidatedCommand('devil:hunt', [
        '--name' => 'Denji',
        '--devil' => 'Zombie Devil',
    ]);
});

it('throws ValidationException for multiple invalid fields', function (): void {
    assertValidationErrors([
        '--name' => 'De',
        '--devil' => '',
        '--rank' => 'god',
        'kills' => 'not-a-number',
    ], [
        'name' => ['The name field must be at least 3 characters.'],
        'devil' => ['The devil field is required.'],
        'rank' => ['The selected rank is invalid.'],
        'kills' => ['The kills field must be an integer.'],
    ]);
});

it('passes with only required valid inputs', function (): void {
    $exitCode = runValidatedCommand('devil:hunt', [
        '--name' => 'Aki',
        '--devil' => 'Gun Devil',
        'kills' => 3,
    ]);

    expect($exitCode)->toBe(Command::SUCCESS)
        ->and(Artisan::output())
        ->toContain('Registered hunt: Aki hunted Gun Devil with 3 kills.');
});

it('passes with all valid inputs including rank', function (): void {
    $exitCode = runValidatedCommand('devil:hunt', [
        '--name' => 'Power',
        '--devil' => 'Bat Devil',
        '--rank' => 'rookie',
        'kills' => 1,
    ]);

    expect($exitCode)->toBe(Command::SUCCESS)
        ->and(Artisan::output())
        ->toContain('Registered hunt: Power hunted Bat Devil with 1 kills.');
});

it('fails when kills is negative', function (): void {
    assertValidationErrors([
        '--name' => 'Kishibe',
        '--devil' => 'Future Devil',
        '--rank' => 'elite',
        'kills' => -5,
    ], [
        'kills' => ['The kills field must be at least 0.'],
    ]);
});
