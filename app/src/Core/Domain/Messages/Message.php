<?php

namespace FluxEco\IliasUserApi\Core\Domain\Messages;

interface Message {
    public function getName(): MessageName;
}