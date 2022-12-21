<?php

namespace Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class Account
{
    private function __construct(
        public string $login,
        public string $authMode,
        public string $externalId = "",
    )
    {

    }

    public static function new(
        string $login,
        string $authMode,
        string $externalId = "",
    )
    {
        return new self(
            ...get_defined_vars()
        );
    }
}