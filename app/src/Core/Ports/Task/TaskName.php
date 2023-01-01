<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Task;

enum TaskName: string
{
    case CREATE_OR_UPDATE_USER = "create-or-update-user";
}