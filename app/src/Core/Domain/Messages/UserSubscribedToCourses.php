<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class UserSubscribedToCourses implements OutgoingMessage
{

    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\CourseRoleName $courseRoleName,
        public ValueObjects\IdType $courseIdType,
        public array $courseIds,
    )
    {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\CourseRoleName $courseRoleName,
        ValueObjects\IdType $courseIdType,
        array $courseIds,
    ): self
    {
        return new self(...get_defined_vars());
    }

    public function getName(): MessageName
    {
        return MessageName::USER_SUBSCRIBED_TO_COURSES;
    }

    public function getAddress() : string
    {
        return MessageName::USER_SUBSCRIBED_TO_COURSES->value;
    }
}