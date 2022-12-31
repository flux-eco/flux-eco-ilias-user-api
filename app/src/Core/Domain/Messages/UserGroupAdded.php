<?php

namespace FluxEco\IliasUserApi\Core\Domain\Messages;

use FluxEco\IliasUserApi\Core\Domain\ValueObjects;

class UserGroupAdded implements Message
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
}