<?php

namespace FluxEco\IliasUserOrbital\Adapters\Dispatchers;

use FluxEco\IliasUserOrbital\Adapters\Config;
use FluxEco\IliasUserOrbital\Core\Ports;
use FluxEco\IliasUserOrbital\Core\Domain;

use FluxEco\DispatcherSynapse;
use Exception;

class HttpMessageDispatcher implements Ports\User\UserMessageDispatcher
{

    private function __construct(
        public readonly Config\Config $config
    ) {

    }

    public static function new(
        Config\Config $config
    ) : self {
        return new self($config);
    }

    /**
     * @throws Exception
     */
    public function dispatch(Domain\Messages\OutgoingMessage $message) : void
    {
        DispatcherSynapse\Adapters\Api\Api::new()->dispatch($message->getAddress(), $message);
    }
}