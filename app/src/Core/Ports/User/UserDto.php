<?php

namespace Flux\IliasUserImportApi\Core\Ports\User;
use Flux\IliasUserImportApi\Core\Domain\ValueObjects;

class UserDto
{
    /**
     * @param ValueObjects\UserData $userData
     * @param ValueObjects\AdditionalField[] $additionalFields
     */
    private function __construct(
        public readonly ValueObjects\UserData $userData,
        public readonly array $additionalFields
    ) {

    }

    /**
     * @param ValueObjects\UserData $userData
     * @param ValueObjects\AdditionalField[] $additionalFields
     * @return static
     */
    public static function new(
        ValueObjects\UserData $userData,
        array $additionalFields
    ): self {
        return new self(...get_defined_vars());
    }
}