<?php

namespace FluxEco\IliasUserOrbital\Core\Domain\Messages;

enum MessageName: string {
    case ADDITIONAL_FIELD_VALUE_CHANGED = "additional-field-value-changed";
    case ADDITIONAL_FIELDS_VALUES_CHANGED = "additional-fields-values-changed";
    case USER_DATA_CHANGED = "user-data-changed";
    case CREATED = "created";
    case USER_SUBSCRIBED_TO_COURSES = "user-subscribed-to-courses";
    case USER_UNSUBSCRIBED_FROM_COURSES = "user-unsubscribed-from-courses";
    case USER_SUBSCRIBED_TO_ROLES = "user-subscribed-to-roles";
    case USER_GROUP_ADDED = "user-group-added";
}