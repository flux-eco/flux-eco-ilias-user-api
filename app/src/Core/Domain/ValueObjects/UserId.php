<?php

namespace FluxEco\IliasUserApi\Core\Domain\ValueObjects;

class UserId
{
    private function __construct(
        public readonly string $id
    ) {

    }

    public static function new(
        string $id
    ) : self {
        return new self(
            $id
        );
    }

    public function isEqual(string $id) : bool
    {
        return ($this->id === $id);
    }
}