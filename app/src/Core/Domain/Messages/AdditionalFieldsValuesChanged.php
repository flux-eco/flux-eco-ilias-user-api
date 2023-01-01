<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class AdditionalFieldsValuesChanged implements OutgoingMessage
{

    /**
     * @param ValueObjects\AdditionalField[] $additionalFieldsValues
     */
    private function __construct(
        public ValueObjects\UserId $userId,
        public array $additionalFieldsValues,
    )
    {

    }

    /**
     * @param ValueObjects\AdditionalField[] $additionalFieldsValues
     */
    public static function new(ValueObjects\UserId $userId, array $additionalFieldsValues): self
    {
        return new self($userId, $additionalFieldsValues);
    }

    public function getName() : MessageName
    {
        return MessageName::ADDITIONAL_FIELDS_VALUES_CHANGED;
    }

    public function getAddress() : string
    {
        return MessageName::ADDITIONAL_FIELDS_VALUES_CHANGED->value;
    }
}