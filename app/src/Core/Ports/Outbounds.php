<?php

namespace Flux\IliasUserImportApi\Core\Ports;

class Outbounds {

    private function __construct(
        public ManagementSystem\ManagementSystemUserQueryRepository $managementSystemUserRepository,
        public Ilias\IliasUserQueryRepository                       $iliasUserRepository,
        public User\UserEventHandler                                $userEventHandler
    )
    {

    }

    public static function new(
        ManagementSystem\ManagementSystemUserQueryRepository $managementSystemUserRepository,
        Ilias\IliasUserQueryRepository                       $iliasUserRepository,
        User\UserEventHandler                                $userEventHandler
    ) {
        return new self(...get_defined_vars());
    }

}