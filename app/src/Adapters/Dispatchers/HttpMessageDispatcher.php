<?php

namespace  FluxEco\IliasUserApi\Adapters\Dispatchers;

use FluxEco\IliasUserApi\Adapters\Config;
use FluxEco\IliasUserApi\Core\Ports;
use FluxEco\IliasUserApi\Core\Domain;

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

    public function dispatch(Domain\Messages\Message $message): void
    {
        match ($message->getName()) {
            Domain\Messages\MessageName::CREATED => $this->publish($message, $this->config->get(Config\EnvName::HTTP_ENDPOINT_USER_CREATED)),
            Domain\Messages\MessageName::USER_DATA_CHANGED => $this->publish($message, $this->config->get(Config\EnvName::HTTP_ENDPOINT_USER_DATA_CHANGED)),
            Domain\Messages\MessageName::ADDITIONAL_FIELD_VALUE_ADDED => $this->publish($message, $this->config->get(Config\EnvName::HTTP_ENDPOINT_ADDITIONAL_FIELD_VALUE_ADDED)),
            Domain\Messages\MessageName::ADDITIONAL_FIELD_VALUE_CHANGED => $this->publish($message, $this->config->get(Config\EnvName::HTTP_ENDPOINT_ADDITIONAL_FIELD_VALUE_CHANGED)),
            Domain\Messages\MessageName::ADDITIONAL_FIELD_VALUE_REMOVED => $this->publish($message, $this->config->get(Config\EnvName::HTTP_ENDPOINT_ADDITIONAL_FIELD_VALUE_REMOVED)),
            Domain\Messages\MessageName::ADDITIONAL_FIELDS_VALUES_CHANGED => $this->publish($message, $this->config->get(Config\EnvName::HTTP_ENDPOINT_ADDITIONAL_FIELDS_VALUES_CHANGED)),
        };
    }

    private function publish(Domain\Messages\Message $message, array $endpoints) : void
    {
        if(count($endpoints) === 0) {
            return;
        }
        $ch = curl_init();
        $responses = [];

        foreach ($endpoints as $endpoint) {
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responses[] = curl_exec($ch);
            curl_close($ch);
        }
    }
}