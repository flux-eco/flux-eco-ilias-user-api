<?php

namespace  FluxEco\IliasUserOrbital\Adapters\Dispatchers;

use FluxEco\IliasUserOrbital\Adapters\Config;
use FluxEco\IliasUserOrbital\Core\Ports;
use FluxEco\IliasUserOrbital\Core\Domain;

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

    public function dispatch(Domain\Messages\OutgoingMessage $message): void
    {
        $tasks = $this->config->getOutgoingTasks($message->getAddress());
        if($tasks === null) {
            return;
        }
        foreach($tasks as $task) {
            $address = $task->address;
            if($task->parameters !== null) {
                foreach($task->parameters as $parameter) {
                    $address = $this->replaceParameter($address, $parameter, $message);
                }
            }
            $payload = $task->message->payload;
            if(property_exists($payload, 'location') === true) {
                if(str_contains('$message.payload#', $payload->location) === true) {
                    $payload = $message;
                }
            }
            $this->publish($payload, $task->server."/".$address);
        }
    }

    private function replaceParameter(string $address, object $parameter, Domain\Messages\OutgoingMessage $message): string {
        if(str_contains('$message.payload#', $parameter->location) === true)  {
            $location = explode('$message.payload#/', $parameter->location)[1];
            $messageAttributePath =  explode('/', $location);
            $value = $message;
            foreach($messageAttributePath as $attributeName) {
                $value = $value->{$attributeName};
            }
            $address = str_replace('{'.$parameter->name.'}', $address, $value);
        }
        return $address;
    }

    private function publish(object $payload, string $address) : void
    {
        echo $address.PHP_EOL;
        $ch = curl_init();
        $responses = [];
        curl_setopt($ch, CURLOPT_URL, $address);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responses[] = curl_exec($ch);
        print_r($responses);
        curl_close($ch);
    }
}