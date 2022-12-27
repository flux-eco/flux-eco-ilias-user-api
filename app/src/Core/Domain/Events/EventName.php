<?php

namespace Flux\IliasUserImportApi\Core\Domain\Events;

enum EventName: string {
    case ADDITIONAL_FIELDS_CHANGED = "additional_fields_changed";
    case USER_DATA_CHANGED = "user_data_changed";
    case CREATED = "created";
    case USER_GROUP_ADDED = "user_group_added";
}