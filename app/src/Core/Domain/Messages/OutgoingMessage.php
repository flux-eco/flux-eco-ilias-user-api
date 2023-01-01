<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\Messages;

interface OutgoingMessage {
    public function getName(): MessageName;
    public function getAddress(): string;
}