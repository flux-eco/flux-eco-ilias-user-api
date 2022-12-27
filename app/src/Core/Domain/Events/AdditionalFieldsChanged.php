<?php

namespace Flux\IliasUserImportApi\Core\Domain\Events;

use Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class AdditionalFieldsChanged implements Event
{

    /**
     * @param ValueObjects\AdditionalField[] $additionalFields
     */
    private function __construct(
        public string $userId,
        public array $additionalFields,
    )
    {

    }

    /**
     * @param ValueObjects\AdditionalField[] $additionalFields
     */
    public static function new(string $userId, array $additionalFields): self
    {
        return new self($userId, $additionalFields);
    }

    public function getName(): string
    {
        return EventName::ADDITIONAL_FIELDS_CHANGED->value;
    }
}