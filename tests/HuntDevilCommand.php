<?php

declare(strict_types=1);

namespace CommandValidator;

use CommandValidator\Actions\ValidateCommand;
use CommandValidator\Attributes\Validatable;
use Illuminate\Console\Command;

#[Validatable([
    'name' => ['required', 'string', 'min:3'],
    'devil' => ['required', 'string'],
    'rank' => ['nullable', 'in:rookie,senior,elite'],
    'kills' => ['required', 'integer', 'min:0'],
])]
final class HuntDevilCommand extends Command
{
    protected $signature = 'devil:hunt
        {--name= : Name of the devil hunter}
        {--devil= : Name of the devil hunted}
        {--rank= : Rank of the hunter (rookie, senior, elite)}
        {kills : Number of devils killed}';

    protected $description = 'Registers a devil hunt in Public Safety database';

    public function handle(): void
    {
        app(ValidateCommand::class)->handle($this);
        $this->info("Registered hunt: {$this->option('name')} hunted {$this->option('devil')} with {$this->argument('kills')} kills.");
    }
}
