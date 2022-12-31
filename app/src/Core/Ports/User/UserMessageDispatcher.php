<?php

namespace FluxEco\IliasUserApi\Core\Ports\User;
use FluxEco\IliasUserApi\Core\Domain;

interface UserMessageDispatcher {
    public function dispatch(Domain\Messages\Message $message);
}