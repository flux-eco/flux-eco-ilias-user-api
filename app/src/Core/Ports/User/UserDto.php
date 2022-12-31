<?php

namespace FluxEco\IliasUserApi\Core\Ports\User;

use FluxEco\IliasUserApi\Core\Domain\ValueObjects;

class UserDto
{
    /**
     * @param ValueObjects\AdditionalField[] $additionalFields
     */
    private function __construct(
        public readonly ValueObjects\UserId $userId,
        public readonly ValueObjects\UserData $userData,
        public readonly array $additionalFields
    ) {

    }

    /**
     * @param ValueObjects\AdditionalField[] $additionalFields
     * @return static
     */
    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\UserData $userData,
        array $additionalFields
    ) : self {
        return new self(...get_defined_vars());
    }

    public static function fromJson(
        string $json
    ) : self {
        $obj = json_decode($json);

        $additionalFields = [];
        if (count($obj->additionalFields) > 0) {
            foreach ($obj->additionalFields as $field) {
                $additionalFields[] = ValueObjects\AdditionalField::new(
                    $field->fieldName,
                    $field->fieldValue,
                );
            }
        }

        return new self(
            ValueObjects\UserId::new(
                $obj->userId->id
            ),
            ValueObjects\UserData::new(
                $obj->userData->email,
                $obj->userData->firstName,
                $obj->userData->lastName,
                $obj->userData->login,
                $obj->userData->authMode,
                $obj->userData->externalId
            ),
            $additionalFields
        );
    }
}