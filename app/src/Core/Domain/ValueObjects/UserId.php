<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class UserId
{
    private function __construct(
        public readonly string $id,
        public IdType $idType
    ) {

    }

    public static function new(
        string $id,
        IdType $idType
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