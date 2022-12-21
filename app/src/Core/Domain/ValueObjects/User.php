<?php

namespace Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class User {
    private function __construct(
        public string $id,
        public string $email,
        public string $firstName,
        public string $lastName,
        public ?Account $account,
        public array $userGroups
    ) {

    }

    public static function new(
        string $id,
        string $email,
        string $firstName,
        string $lastName,
        ?Account $account,
        array $userGroups
    ): self
    {
        return new self(
            ...get_defined_vars()
        );
    }
}