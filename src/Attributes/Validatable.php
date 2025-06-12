<?php

declare(strict_types=1);

namespace CommandValidator\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Validatable
{
    /**
     * @param  array<string, array<string>>  $rules
     */
    public function __construct(
        public array $rules
    ) {}
}
