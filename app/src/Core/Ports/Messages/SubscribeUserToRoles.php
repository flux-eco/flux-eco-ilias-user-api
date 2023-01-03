<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class SubscribeUserToRoles implements IncomingMessage
{

    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\IdType $roleIdType,
        public array $roleIds
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\IdType $roleIdType,
        array $roleIds
    ) : self {
        return new self(
            ...get_defined_vars()
        );
    }

    public static function fromJson(string $json)
    {
        $obj = json_decode($json);
        return new self(
            ValueObjects\UserId::new(
                $obj->userId->id,
                ValueObjects\IdType::from($obj->userId->idType),
            ),
            ValueObjects\IdType::from($obj->roleIdType),
            $obj->roleIds
        );
    }

    public function getName() : IncomingMessageName
    {
        return IncomingMessageName::SUBSCRIBE_USER_TO_COURSES;
    }

}