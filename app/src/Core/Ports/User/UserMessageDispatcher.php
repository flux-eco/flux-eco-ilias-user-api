<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\User;
use FluxEco\IliasUserOrbital\Core\Domain;

interface UserMessageDispatcher {
    public function dispatch(Domain\Messages\OutgoingMessage $messageToDispatch);
}