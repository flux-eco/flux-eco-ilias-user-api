<?php

namespace Flux\IliasUserImportApi\Core\Ports\ManagementSystem;
use Flux\IliasUserImportApi\Core\Ports;

interface ManagementSystemUserQueryRepository
{
    /**
     * @param string $contextId
     * @return Ports\User\UserDto[]
     */
    public function getUserOfContext(string $contextId): array;
}