<?php

namespace FluxEco\IliasUserOrbital\Adapters\Logger;

use FluxEco\IliasUserOrbital\Adapters\Config;

final readonly class HttpMessageMessageLogger implements Config\MessageLogger
{
    //todo
    private function __construct(
        private string $name,
        private string $serverProtocol = "http",
        private string $serverUrl = "flux-eco-message-logger-orbital",
        private string $serverPort = "9501"
    ) {

    }

    public static function new(string $name)
    {
        return new self($name);
    }

    public function log(object $payload, string $address) : void
    {
        $this->publish($payload, $address);
    }

    private function publish(object $payload, string $address) : void
    {
        echo json_encode($payload, JSON_PRETTY_PRINT) . PHP_EOL;
        $ch = curl_init();
        $responses = [];
        curl_setopt($ch, CURLOPT_URL,$this->serverProtocol . "://" . $this->serverUrl . ":" . $this->serverPort . "/" . $address);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'x-eco-orbital: '.$this->name]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responses[] = curl_exec($ch);
        print_r($responses);
        curl_close($ch);
    }
}