<?php

namespace Flux\IliasUserImportApi\Core\Ports\ManagementSystem;

interface ManagementSystemUserRepository
{
    public function getUserOfContext(string $contextId);
}