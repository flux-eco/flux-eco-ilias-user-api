<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class UserId
{
    private function __construct(
        public readonly string $id,
        public string $idType
    ) {

    }

    public static function new(
        string $id,
        string $idType = "user-import-id"
    ) : self {
        return new self(
            $id,
            $idType
        );
    }

    public function isEqual(string $id) : bool
    {
        return ($this->id === $id);
    }
}