<?php

namespace FluxEco\IliasUserApi\Core\Ports;

class  Outbounds {

    private function __construct(
        public readonly User\UserRepository $userRepository,
        public readonly User\UserMessageDispatcher $userMessageDispatcher
    )
    {

    }

    public static function new(
        User\UserRepository $userRepository,
        User\UserMessageDispatcher $userMessageDispatcher
    ): self {
        return new self(...get_defined_vars());
    }

}