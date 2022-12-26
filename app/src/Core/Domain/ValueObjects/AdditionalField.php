<?php

namespace Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class AdditionalField
{
    private function __construct(
        string $schemaId,
        string $fieldName,
        string|int $fieldValue,
    ) {

    }

    public static function new(
        string $schemaId,
        string $fieldName,
        string|int $fieldValue,
    ) {
        return new self(
            ...get_defined_vars()
        );
    }
}