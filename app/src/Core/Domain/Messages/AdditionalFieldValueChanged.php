<?php

namespace FluxEco\IliasUserApi\Core\Domain\Messages;

use FluxEco\IliasUserApi\Core\Domain\ValueObjects;

class AdditionalFieldValueChanged implements Message
{
    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\AdditionalField $newAdditionalFieldValue,
        public ValueObjects\AdditionalField $oldAdditionalFieldValue
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\AdditionalField $newAdditionalFieldValue,
        ValueObjects\AdditionalField $oldAdditionalFieldValue
    ) : self {
        return new self($userId, $newAdditionalFieldValue, $oldAdditionalFieldValue);
    }

    public function getName() : MessageName
    {
        return MessageName::ADDITIONAL_FIELD_VALUE_CHANGED;
    }
}