<?php

namespace FluxEco\IliasUserOrbital\Adapters\Config;

interface MessageLogger {
    public function log(object $payload, string $address): void;
}