<?php

namespace FluxEco\IliasUserApi\Core\Domain\Messages;

use FluxEco\IliasUserApi\Core\Domain\ValueObjects;

class AdditionalFieldValueRemoved implements Message
{
    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\AdditionalField $oldAdditionalFieldValue,
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\AdditionalField $oldAdditionalFieldValue
    ) : self {
        return new self($userId, $oldAdditionalFieldValue);
    }

    public function getName() : MessageName
    {
        return MessageName::ADDITIONAL_FIELD_VALUE_REMOVED;
    }
}