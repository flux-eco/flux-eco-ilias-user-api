<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class CreateOrUpdateUser implements IncomingMessage
{
    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\UserData $userData,
        public array $additionalFields
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\UserData $userData,
        array $additionalFields
    ) : self {
        return new self(
            ...get_defined_vars()
        );
    }

    public static function fromJson(string $json) {
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
                $obj->userId->id,
                ValueObjects\IdType::from($obj->userId->idType)
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

    public function getName() : IncomingMessageName
    {
        return IncomingMessageName::CREATE_OR_UPDATE_USER;
    }
}