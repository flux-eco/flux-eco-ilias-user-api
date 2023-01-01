<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;

interface IncomingMessage {
    public function getName(): IncomingMessageName;
}