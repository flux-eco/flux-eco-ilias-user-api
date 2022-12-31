<?php

namespace FluxEco\IliasUserApi\Core\Domain\Messages;

use FluxEco\IliasUserApi\Core\Domain\ValueObjects;

class AdditionalFieldsValuesChanged implements Message
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
}