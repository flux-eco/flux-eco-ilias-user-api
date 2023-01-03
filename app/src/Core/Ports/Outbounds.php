<?php

namespace FluxEco\IliasUserOrbital\Core\Ports;

class  Outbounds {

    private function __construct(
        public readonly User\UserRepository $userRepository,
        public readonly User\UserMessageDispatcher $userMessageDispatcher,
        public readonly Course\CourseRepository $courseRepository
    )
    {

    }

    public static function new(
        User\UserRepository $userRepository,
        User\UserMessageDispatcher $userMessageDispatcher,
        Course\CourseRepository $courseRepository
    ): self {
        return new self(...get_defined_vars());
    }

}