<?php

namespace Flux\IliasUserImportApi\Core\Ports;

class Outbounds {

    private function __construct(
        public ManagementSystem\ManagementSystemUserRepository $managementSystemUserRepository,
        public Ilias\IliasUserRepository $userRepository
    )
    {

    }

    public static function new(
        ManagementSystem\ManagementSystemUserRepository $managementSystemUserRepository,
        Ilias\IliasUserRepository $userRepository
    ) {
        return new self(...get_defined_vars());
    }

}