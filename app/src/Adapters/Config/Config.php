<?php

namespace FluxEco\IliasUserApi\Adapters\Config;

class Config
{
    private function __construct(

    ) {

    }

    public static function new() : self
    {
        return new self();
    }

    public function get(EnvName $envName):array|int|string {
        return $envName->toConfigValue();
    }
}