<?php

namespace FluxEco\IliasUserOrbital\Adapters\Dispatchers;

use FluxEco\IliasUserOrbital\Adapters\Config;
use FluxEco\IliasUserOrbital\Core\Ports;
use FluxEco\IliasUserOrbital\Core\Domain;
use stdClass;

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

    public function dispatch(Domain\Messages\OutgoingMessage $message) : void
    {
        $tasks = $this->config->getOutgoingTasks(str_replace(" ", "%20", $message->getAddress()));
        if ($tasks === null) {
            return;
        }

        foreach ($tasks as $task) {
            if (str_contains($task->address->server->protocol, "http") === false) {
                continue;
            }

            if (property_exists($task, 'required') === true) {
                $abort = false;
                foreach ($task->required as $required) {
                    if (str_contains($required, '{$message.') === true) {
                        $propertyName = rtrim(ltrim($required, '{$message.'), '}');
                        if (property_exists($message,
                                $propertyName) === false || $message->{$propertyName} === null || $message->{$propertyName} === "") {
                            $abort = true;
                        }
                    }
                }
                if ($abort === true) {
                    continue;
                }
            }

            $addressPath = $task->address->path;
            if ($task->address->parameters !== null) {
                foreach ((array) $task->address->parameters as $parameterName => $parameter) {
                    $addressPath = $this->replaceParameter($addressPath, $parameterName, $parameter, $message);
                }
            }

            $messageToDispatch = $task->messageToDispatch;

            if (property_exists($messageToDispatch, '$merge') === true) {
                if (str_contains($messageToDispatch->{'$merge'}, '{$message}') === true) {
                    unset($messageToDispatch->{'$merge'});
                    $messageToDispatch = (object) array_merge(
                        (array) $messageToDispatch, (array) $message);
                }
            }

            if (property_exists($messageToDispatch, '$location') === true) {
                if (str_contains($messageToDispatch->{'$location'}, '{$message}') === true) {
                    $messageToDispatch = $message;
                }
            }

            if (property_exists($messageToDispatch, '$transform') === true) {
                $messageToDispatch = $this->handleTransformation($messageToDispatch->{'$transform'}, $message);
            }

            $this->publish($messageToDispatch,
                $task->address->server->protocol . "://" . $task->address->server->url . "/" . $addressPath);
        }
    }

    //todo refactor this (@see https://raw.githubusercontent.com/learn-medi-ch/configs/main/flux-eco-ilias-user-orbital/dev/outgoing/additional-field-name/Ausbildungskurs%20(Klasse)/additional-field-value-changed.json)
    private function handleTransformation(object $properties, Domain\Messages\OutgoingMessage $message) : object
    {
        $messageToDispatch = new stdClass();
        foreach ($properties as $propertyKey => $propertyValue) {
            if (is_object($propertyValue) === true) {
                $messageToDispatch->{$propertyKey} = $this->handleTransformation($propertyValue, $message);
                continue;
            }
            if (str_contains($propertyValue, '{$message.') === true) {
                $prefix = strstr($propertyValue, '{', true);
                $suffix = strstr($propertyValue, '}');
                if($suffix === '}')  {
                    $suffix = "";
                }

                $messagePropertyKey = rtrim(ltrim($propertyValue, $prefix . '{$message.'), '}' . $suffix);
                if(is_object($message->{$messagePropertyKey}) === true) {
                    $propertyValue = $message->{$messagePropertyKey};
                }  else  {
                    $propertyValue = $prefix . $message->{$messagePropertyKey} . $suffix;
                }
            }
            $messageToDispatch->{$propertyKey} = $propertyValue;
        }
        return $messageToDispatch;
    }

    private function replaceParameter(
        string $address,
        string $parameterName,
        object $parameter,
        Domain\Messages\OutgoingMessage $message
    ) : string {
        if (str_contains($parameter->location, '{$message}') === true) {
            $location = ltrim($parameter->location, '{$message}');
            $messageAttributePath = explode('/', $location);
            $value = $message;
            foreach ($messageAttributePath as $attributeName) {
                $value = $value->{$attributeName};
            }
            $address = str_replace('{' . $parameterName . '}', $value, $address);
        }
        return $address;
    }

    private function publish(object $payload, string $address) : void
    {
        echo "send message: " . PHP_EOL;
        echo $address . PHP_EOL;
        echo json_encode($payload, JSON_PRETTY_PRINT) . PHP_EOL;
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