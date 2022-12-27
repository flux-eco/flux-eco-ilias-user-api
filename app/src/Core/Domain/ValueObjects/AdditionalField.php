<?php

namespace Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class AdditionalField
{
    private function __construct(
        public string $fieldName,
        public string|int $fieldValue,
    ) {

    }

    public static function new(
        string $fieldName,
        string|int $fieldValue,
    ) {
        return new self(
            ...get_defined_vars()
        );
    }

    public function isEqual(AdditionalField $additionalField): bool {
        return (serialize($this) === serialize($additionalField));
    }
}