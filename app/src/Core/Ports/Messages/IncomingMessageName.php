<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;

enum IncomingMessageName: string
{
    case CREATE_OR_UPDATE_USER = "create-or-update-user";
    case SUBSCRIBE_TO_COURSES = "subscribe-to-courses";
    case UNSUBSCRIBE_FROM_COURSES = "unsubscribe-from-courses";
    case SUBSCRIBE_TO_ROLES = "subscribe-to-roles";
}