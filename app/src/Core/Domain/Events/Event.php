<?php

namespace Flux\IliasUserImportApi\Core\Domain\Events;

interface Event {
    public function getName(): string;
}