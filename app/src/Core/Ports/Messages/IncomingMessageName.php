<?php

namespace FluxEco\IliasUserOrbital\Core\Ports\Messages;

enum IncomingMessageName: string {
    case CREATE_OR_UPDATE_USER = "create-or-update-user";
    case SUBSCRIBE_TO_COURSES_BY_REF_IDS = "subscribe-to-courses-by-ref-ids";
}