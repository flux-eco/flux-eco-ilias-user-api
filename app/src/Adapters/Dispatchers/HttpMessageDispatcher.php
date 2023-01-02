<?php

namespace FluxEco\IliasUserOrbital\Adapters\Dispatchers;

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
    public function dispatch(Domain\Messages\OutgoingMessage $messageToDispatch) : void
    {
        $tasks = $this->config->getOutgoingTasks(str_replace(" ","%20",$messageToDispatch->getAddress()));
        if ($tasks === null) {
            return;
        }

        foreach ($tasks as $task) {
            if (str_contains($task->address->server->protocol, "http") === false) {
                continue;
            }

            $addressPath = $task->address->path;
            if ($task->address->parameters !== null) {
                foreach ((array)$task->address->parameters as $parameterName => $parameter) {
                    $addressPath = $this->replaceParameter($addressPath, $parameterName, $parameter, $messageToDispatch);
                }
            }
            $message = $task->message;
            if (property_exists($message, '$merge') === true) {
                if (str_contains($message->{'$merge'}, '{$message}') === true) {
                    unset($message->{'$merge'});
                    $message = (object) array_merge(
                        (array) $message, (array) $messageToDispatch);
                }
            }

            if (property_exists($message, '$location') === true) {
                if (str_contains($message->{'$location'}, '{$message}') === true) {
                    $message = $messageToDispatch;
                }
            }

            $this->publish($message,
                $task->address->server->protocol . "://" . $task->address->server->url . "/" . $addressPath);
        }
    }

    private function replaceParameter(string $address, string $parameterName, object $parameter, Domain\Messages\OutgoingMessage $message): string {
        if(str_contains($parameter->location, '{$message}') === true)  {
            $location = ltrim($parameter->location, '{$message}');
            $messageAttributePath =  explode('/', $location);
            $value = $message;
            foreach($messageAttributePath as $attributeName) {
                $value = $value->{$attributeName};
            }
            $address = str_replace('{'.$parameterName.'}', $value, $address);
        }
        return $address;
    }


    private function publish(object $payload, string $address) : void
    {
        echo "send message: ". PHP_EOL;
        echo $address . PHP_EOL;
        echo json_encode($payload, JSON_PRETTY_PRINT). PHP_EOL;
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