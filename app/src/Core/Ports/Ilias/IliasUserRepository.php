<?php

namespace Flux\IliasUserImportApi\Core\Ports\Ilias;


interface IliasUserRepository {
    public function createOrUpdateUser();
    public function setIs();
}