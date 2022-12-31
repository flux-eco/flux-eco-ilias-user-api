<?php

namespace FluxEco\IliasUserApi\Core\Ports\Task;

enum TaskName: string
{
    case CREATE_OR_UPDATE_USER = "create-or-update-user";
}