<?php

namespace Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class UserGroup
{

    private function __construct(
        public string $id,
        public string $title
    )
    {

    }

    public static function new(
        string $id,
        string $title
    ): self
    {
        return new self(
         ...get_defined_vars()
        );
    }
}