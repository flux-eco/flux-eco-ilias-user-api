<?php

namespace Flux\IliasUserImportApi\Core\Ports\User;
use Flux\IliasUserImportApi\Core\Domain;

interface UserEventHandler {
    public function handle(Domain\Events\Event $event);
}