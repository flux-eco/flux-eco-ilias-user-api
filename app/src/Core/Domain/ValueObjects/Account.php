<?php

namespace Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class Account
{
    private function __construct(
        public string $login,
        public string $externalId,
        public string $authMode,
    )
    {

    }

    public static function new(
        string $login,
        string $externalId = "",
        string $authMode = "default"
    )
    {
        return new self(
            ...get_defined_vars()
        );
    }
}