<?php

namespace Flux\IliasUserImportApi\Core\Domain;

use  Flux\IliasUserImportApi\Core\Ports;

class Actor
{

    private function __construct(
        private Ports\Outbounds $outbounds
    )
    {

    }

    public static function new(Ports\Outbounds $outbounds)
    {
        return new self($outbounds);
    }

    public function importUsers(string $contextId, callable $publish)
    {
        $users = $this->outbounds->managementSystemUserRepository->getUserOfContext($contextId);

        //todo


        $publish('userImported: '.print_r($users,true));
    }


}