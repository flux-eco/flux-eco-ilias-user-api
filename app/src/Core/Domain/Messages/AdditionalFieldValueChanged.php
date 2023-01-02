<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class AdditionalFieldValueChanged implements OutgoingMessage
{
    private function __construct(
        public ValueObjects\UserId $userId,
        public string $additionalFieldName,
        public null|int|string $newAdditionalFieldValue,
        public null|int|string $oldAdditionalFieldValue
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        string $additionalFieldName,
        null|int|string $newAdditionalFieldValue,
        null|int|string $oldAdditionalFieldValue
    ) : self {
        return new self($userId, $additionalFieldName, $newAdditionalFieldValue, $oldAdditionalFieldValue);
    }

    public function getName() : MessageName
    {
        return MessageName::ADDITIONAL_FIELD_VALUE_CHANGED;
    }

    public function getAddress() : string
    {
        return "additional-field-name/".$this->additionalFieldName."/".MessageName::ADDITIONAL_FIELD_VALUE_CHANGED->value;
    }
}