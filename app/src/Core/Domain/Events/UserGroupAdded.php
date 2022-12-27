<?php

namespace Flux\IliasUserImportApi\Core\Domain\Events;

use Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class UserGroupAdded implements Event
{

    private function __construct(
        public ValueObjects\UserGroup $userGroup
    )
    {

    }

    public static function new(ValueObjects\UserGroup $userGroup): self
    {
        return new self($userGroup);
    }

    public function getName(): string
    {
        return EventName::USER_GROUP_ADDED->value;
    }
}