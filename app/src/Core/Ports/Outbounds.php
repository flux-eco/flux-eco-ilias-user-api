<?php

namespace Flux\IliasUserImportApi\Core\Ports;

class Outbounds {

    private function __construct(
        public ManagementSystem\ManagementSystemUserRepository $managementSystemUserRepository,
        public Ilias\IliasUserRepository $iliasUserRepository
    )
    {

    }

    public static function new(
        ManagementSystem\ManagementSystemUserRepository $managementSystemUserRepository,
        Ilias\IliasUserRepository $iliasUserRepository
    ) {
        return new self(...get_defined_vars());
    }

}