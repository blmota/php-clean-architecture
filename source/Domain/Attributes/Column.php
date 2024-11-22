<?php

namespace Source\Domain\Attributes;

use Attribute;

#[Attribute]
class Column
{
    public function __construct(
        public string $name,
        public string $type,
        public ?string $customConverter = null // Exemplo: 'DateTime'
    ) {}
}
