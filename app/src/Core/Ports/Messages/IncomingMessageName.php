<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;

enum IncomingMessageName: string
{
    case CREATE_OR_UPDATE_USER = "create-or-update-user";
    case SUBSCRIBE_USER_TO_COURSES = "subscribe-user-to-courses";
    case SUBSCRIBE_USER_TO_COURSE  = "subscribe-user-to-course";
    case UNSUBSCRIBE_USER_FROM_COURSES = "unsubscribe-user-from-courses";
    case SUBSCRIBE_USER_TO_COURSE_TREE = "subscribe-user-to-course-tree";
    case SUBSCRIBE_USER_TO_ROLES = "subscribe-user-to-roles";
}