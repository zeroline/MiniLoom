<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Validation;

enum ValidatorRule : string
{
    case REQUIRED = "checkRequired";

    case IS_EMAIL = "checkEmail";
    case IS_URL = "checkUrl";
    case IS_NUMBER = "checkNumber";
    case IS_INT = "checkInt";
    case IS_FLOAT = "checkFloat";
    case IS_BOOL = "checkBool";
    case IS_STRING = "checkString";
    case IS_DATE = "checkDate";
    case IS_ARRAY = "checkArray";
    case IS_OBJECT = "checkObject";
    case IS_OBJECT_OR_ARRAY = "checkObjectOrArray";

    case NUM_MIN = "checkNumberMinValue";
    case NUM_MAX = "checkNumberMaxValue";
    case NUM_RANGE = "checkNumberRange";

    case INT_MIN = "checkIntMinValue";
    case INT_MAX = "checkIntMaxValue";
    case INT_RANGE = "checkIntRange";

    case FLOAT_MIN = "checkFloatMinValue";
    case FLOAT_MAX = "checkFloatMaxValue";
    case FLOAT_RANGE = "checkFloatRange";

    case STR_MIN = "checkStrMinLength";
    case STR_MAX = "checkStrMaxLength";
    case STR_RANGE = "checkStrLengthRange";
    case STR_BEGINS_WITH = "checkStrBeginsWith";
    case STR_ENDS_WITH = "checkStrEndsWith";
    case STR_CONTAINS = "checkStrContains";

    case IN_ARRAY = "checkInArray";
    case NOT_IN_ARRAY = "checkNotInArray";
    case HAS_COUNT = "checkCount";
    case HAS_COUNT_MIN = "checkMinCount";
    case HAS_COUNT_MAX = "checkMaxCount";
    case HAS_COUNT_RANGE = "checkCountRange";

    case DATE_IS_BEFORE = "checkDateIsBefore";
    case DATE_IS_AFTER = "checkDateIsAfter";
    case DATE_IS_BETWEEN = "checkDateIsBetween";

    case IS_VALID_BIC = "checkBIC";
    case IS_VALID_IBAN = "checkIBAN";

    case IS_VALID_BASE64 = "checkBase64";
    case IS_VALID_JSON = "checkJson";

    case IS_EQUAL = "checkEqual";
    case IS_NOT_EQUAL = "checkNotEqual";
    case IS_REGEX = "checkRegex";

    case CUSTOM = "checkCustom";
}
