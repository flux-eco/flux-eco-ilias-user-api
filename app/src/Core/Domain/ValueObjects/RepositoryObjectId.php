<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class RepositoryObjectId
{
    private function __construct(
        public readonly string|int $id,
        public readonly IdType $idType
    ) {

    }

    public static function new(
        string|int $id,
        IdType $idType
    ) : self {
        return new self(
            $id,
            $idType
        );
    }

    public function isEqual(RepositoryObjectId $obj) : bool
    {
        return (serialize($this) === serialize($obj));
    }
}