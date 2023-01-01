<?php

namespace FluxEco\IliasUserOrbital\Adapters\Config;

enum EnvName: string
{
    case FLUX_ECO_ILIAS_USER_ORBITAL_API_CONFIG_PATH = 'FLUX_ECO_ILIAS_USER_ORBITAL_API_CONFIG_PATH';

    public function toConfigValue() : string|int|array
    {
        return getenv($this->value);
    }
}