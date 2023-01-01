<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class UserGroupAdded implements OutgoingMessage
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

    public function getName(): MessageName
    {
        return MessageName::USER_GROUP_ADDED;
    }

    public function getAddress() : string
    {
        // TODO: Implement getAddress() method.
    }
}