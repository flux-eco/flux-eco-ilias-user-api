<?php

namespace Flux\IliasUserImportApi\Core\Ports\Ilias;


interface IliasUserRepository {
    public function getAll();
    public function createOrUpdateUser();
    public function setIs();
}