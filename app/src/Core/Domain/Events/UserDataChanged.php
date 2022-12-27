<?php

namespace Flux\IliasUserImportApi\Core\Domain\Events;

use Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class UserDataChanged implements Event
{

    private function __construct(
        public ValueObjects\UserData $userData,
    )
    {

    }

    public static function new(ValueObjects\UserData $userData): self
    {
        return new self($userData);
    }

    public function getName(): string
    {
        return EventName::USER_DATA_CHANGED->value;
    }
}