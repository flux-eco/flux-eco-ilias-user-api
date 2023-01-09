<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class Created implements OutgoingMessage
{

    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\UserData $userData
    )
    {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\UserData $userData
    ): self
    {
        return new self(...get_defined_vars());
    }

    public function getName(): MessageName
    {
        return MessageName::CREATED;
    }

    public function getAddress() : string
    {
        return MessageName::CREATED->value;
    }
}