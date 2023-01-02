<?php

namespace FluxEco\IliasUserOrbital\Adapters\Config;

class Task
{
    private function __construct(
        public object $address,
        public object $message
    ) {

    }

    public static function new(
        object $address,
        object $message
    ) : self {
        return new self(...get_defined_vars());
    }
}