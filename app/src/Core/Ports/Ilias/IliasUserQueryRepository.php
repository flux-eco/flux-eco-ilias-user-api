<?php

namespace Flux\IliasUserImportApi\Core\Ports\Ilias;

use Flux\IliasUserImportApi\Core\Ports;

interface IliasUserQueryRepository
{
    /**
     * @return Ports\User\UserDto[]
     */
    public function getAll(): array;
}