<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;

use FluxEco\IliasUserOrbital\Core\Domain\ValueObjects;

class SubscribeUserToCourseTree implements IncomingMessage
{
    private function __construct(
        public readonly ValueObjects\UserId $userId,
        public readonly ValueObjects\CourseRoleName $courseRoleName,
        public readonly ValueObjects\RepositoryObjectId $categoryId
    ) {

    }

    public static function new(
        ValueObjects\UserId $userId,
        ValueObjects\CourseRoleName $courseRoleName,
        ValueObjects\RepositoryObjectId $categoryId
    ) : self {
        return new self(
            ...get_defined_vars()
        );
    }

    public static function fromJson(string $json) : self
    {
        $obj = json_decode($json);
        return new self(
            ValueObjects\UserId::new(
                $obj->userId->id,
                ValueObjects\IdType::from($obj->userId->idType),
            ),
            ValueObjects\CourseRoleName::from($obj->courseRoleName),
            ValueObjects\RepositoryObjectId::new($obj->categoryId->id, ValueObjects\IdType::from($obj->categoryId->idType))
        );
    }

    public function getName() : IncomingMessageName
    {
        return IncomingMessageName::SUBSCRIBE_USER_TO_COURSE_TREE;
    }
}