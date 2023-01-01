<?php

namespace FluxEco\IliasUserOrbital\Adapters\Config;

class Task
{
    private function __construct(
        public string $server,
        public string $address,
        public object $parameters,
        public object $message
    ) {

    }

    public static function new(
        string $server,
        string $address,
        object $parameters,
        object $message
    ) : self {
        return new self(...get_defined_vars());
    }
}