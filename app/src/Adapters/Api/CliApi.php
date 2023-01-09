<?php

namespace FluxEco\IliasUserOrbital\Adapters\Api;

use FluxIliasRestApiClient\Adapter\Api\IliasRestApiClient;
use FluxEco\IliasUserOrbital\Adapters;
use FluxEco\IliasUserOrbital\Core\Ports;

class CliApi
{
    private $counter = 0;

    private function __construct(
        private Ports\Service $service
    ) {

    }

    public static function new() : self
    {
        $iliasRestApiClient = IliasRestApiClient::new();
        $config = Adapters\Config\Config::new();

        return new self(
            Ports\Service::new(
                Ports\Outbounds::new(
                    Adapters\Repositories\IliasUser\IliasUserRepository::new($iliasRestApiClient),
                    Adapters\Dispatchers\HttpMessageDispatcher::new($config),
                    Adapters\Repositories\IliasCourse\IliasCourseRepository::new($iliasRestApiClient)
                )
            )
        );
    }

    public function unsubscribeUserFromCourses(Ports\Messages\UnsubscribeUserFromCourses $unsubscribeUserFromCourses) : void
    {
        $this->service->unsubscribeUserFromCourses($unsubscribeUserFromCourses, fn($responseObject) => $this->publish($responseObject));
    }

    public function publish(object|string $responseObject) : void
    {
        $this->counter = $this->counter + 1;
        $response = $responseObject;
        if (is_string($responseObject) === false) {
            $response = json_encode($responseObject, JSON_PRETTY_PRINT);
        }

        echo $response." ".$this->counter . PHP_EOL;
    }

}