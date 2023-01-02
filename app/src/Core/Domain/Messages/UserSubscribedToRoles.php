<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class UserSubscribedToRoles implements OutgoingMessage
{

    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\IdType $roleIdType,
        public array $roleIds,
    )
    {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\IdType $roleIdType,
        array $roleIds,
    ): self
    {
        return new self(...get_defined_vars());
    }

    public function getName(): MessageName
    {
        return MessageName::USER_SUBSCRIBED_TO_ROLES;
    }

    public function getAddress() : string
    {
        return MessageName::USER_SUBSCRIBED_TO_ROLES->value;
    }
}