<?php

namespace FluxEco\IliasUserApi\Core\Domain\Messages;

use FluxEco\IliasUserApi\Core\Domain\ValueObjects;

class Created implements Message
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
}