<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class SubscribeToCourses implements IncomingMessage
{

    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\CourseRoleName $courseRoleName,
        public ValueObjects\IdType $courseIdType,
        public array $courseIds
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\CourseRoleName $courseRoleName,
        ValueObjects\IdType $courseIdType,
        array $courseIds
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
            ValueObjects\CourseRoleName::from($obj->courseRoleName),
            ValueObjects\IdType::from($obj->courseIdType),
            $obj->courseIds
        );
    }

    public function getName() : IncomingMessageName
    {
        return IncomingMessageName::SUBSCRIBE_TO_COURSES;
    }

}