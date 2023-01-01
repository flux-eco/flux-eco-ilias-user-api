<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;
use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;


class SubscribeToCoursesByRefIds implements IncomingMessage {

    private function __construct(
        public ValueObjects\UserId $userId,
        public ValueObjects\CourseRoleName $courseRoleName,
        public array $refIds
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\CourseRoleName $courseRoleName,
        array $refIds
    ) : self {
        return new self(
            ...get_defined_vars()
        );
    }

    public static function fromJson(string $json, string $userIdType, string $userId) {
        $obj = json_decode($json);
        return new self(
            ValueObjects\UserId::new(
                $userId,
                $userIdType
            ),
            ValueObjects\CourseRoleName::from($obj->courseRoleName),
            $obj->refIds
        );
    }

    public function getName() : IncomingMessageName
    {
        return IncomingMessageName::SUBSCRIBE_TO_COURSES_BY_REF_IDS;
    }

}