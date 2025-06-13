# Command Validator

> A lightweight Laravel package to validate command arguments and options using Laravel's validation.

**Command Validator** enables automatic validation of Laravel command inputs using a simple attribute. Define rules on your command class, and invalid input will be handled gracefully; including proper error messages and test-safe exceptions.

---

## Installation

Install the package via Composer:

```bash
composer require benjamimwalker/command-validator
```

---

## Usage

Apply the `Validatable` attribute to any Artisan command class to define validation rules for arguments and options.

### Example

```php
use CommandValidator\Attributes\Validatable;
use Illuminate\Console\Command;

#[Validatable([
    'name' => ['required', 'string', 'min:3'],
    'email' => ['required', 'email'],
    'steps' => ['string'],
])]
class Dummy extends Command
{
    protected $signature = 'u:c
        {--name= : The name of the user}
        {--email= : The email of the user}
        {steps}';

    protected $description = 'Command description';

    public function handle()
    {
        // Safe to use validated inputs here
    }
}
```

## License

This package is open-sourced software licensed under the [MIT license](https://github.com/benjamimWalker/command-validator/blob/master/LICENSE.md).
