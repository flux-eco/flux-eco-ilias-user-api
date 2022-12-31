<?php

namespace FluxEco\IliasUserApi\Core\Domain\Messages;

use FluxEco\IliasUserApi\Core\Domain\ValueObjects;

class AdditionalFieldValueAdded implements Message
{
    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\AdditionalField $newAdditionalFieldValue,
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\AdditionalField $newAdditionalFieldValue
    ) : self {
        return new self($userId, $newAdditionalFieldValue);
    }

    public function getName() : MessageName
    {
        return MessageName::ADDITIONAL_FIELD_VALUE_ADDED;
    }
}