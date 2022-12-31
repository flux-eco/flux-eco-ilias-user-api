<?php

namespace FluxEco\IliasUserApi\Core\Domain\Messages;

enum MessageName: string {
    case ADDITIONAL_FIELD_VALUE_ADDED = "additional-field-value-added";
    case ADDITIONAL_FIELD_VALUE_CHANGED = "additional-field-value-changed";
    case ADDITIONAL_FIELD_VALUE_REMOVED = "additional-field-value-removed";
    case ADDITIONAL_FIELDS_VALUES_CHANGED = "additional-fields-values-changed";
    case USER_DATA_CHANGED = "user-data-changed";
    case CREATED = "created";
    case USER_GROUP_ADDED = "user-group-added";
}