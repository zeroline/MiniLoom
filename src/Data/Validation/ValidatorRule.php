<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 */

namespace zeroline\MiniLoom\Data\Validation;

class ValidatorRule
{
    public const REQUIRED = "checkRequired";

    public const IS_EMAIL = "checkEmail";
    public const IS_URL = "checkUrl";
    public const IS_NUMBER = "checkNumber";
    public const IS_INT = "checkInt";
    public const IS_FLOAT = "checkFloat";
    public const IS_BOOL = "checkBool";
    public const IS_STRING = "checkString";
    public const IS_DATE = "checkDate";
    public const IS_ARRAY = "checkArray";
    public const IS_OBJECT = "checkObject";
    public const IS_OBJECT_OR_ARRAY = "checkObjectOrArray";

    public const NUM_MIN = "checkNumberMinValue";
    public const NUM_MAX = "checkNumberMaxValue";
    public const NUM_RANGE = "checkNumberRange";

    public const INT_MIN = "checkIntMinValue";
    public const INT_MAX = "checkIntMaxValue";
    public const INT_RANGE = "checkIntRange";

    public const FLOAT_MIN = "checkFloatMinValue";
    public const FLOAT_MAX = "checkFloatMaxValue";
    public const FLOAT_RANGE = "checkFloatRange";

    public const STR_MIN = "checkMinStrLength";
    public const STR_MAX = "checkMaxStrLength";
    public const STR_RANGE = "checkStrLengthRange";
    public const STR_BEGINS_WITH = "checkStrBeginsWith";
    public const STR_ENDS_WITH = "checkStrEndsWith";
    public const STR_CONTAINS = "checkStrContains";

    public const IN_ARRAY = "checkInArray";
    public const NOT_IN_ARRAY = "checkNotInArray";
    public const HAS_COUNT = "checkCount";
    public const HAS_COUNT_MIN = "checkMinCount";
    public const HAS_COUNT_MAX = "checkMaxCount";
    public const HAS_COUNT_RANGE = "checkCountRange";

    public const DATE_IS_BEFORE = "checkDateIsBefore";
    public const DATE_IS_AFTER = "checkDateIsAfter";
    public const DATE_IS_BETWEEN = "checkDateIsBetween";

    public const IS_VALID_BIC = "checkBIC";
    public const IS_VALID_IBAN = "checkIBAN";

    public const IS_VALID_BASE64 = "checkBase64";
    public const IS_VALID_JSON = "checkJson";

    public const IS_EQUAL = "checkEqual";
    public const IS_NOT_EQUAL = "checkNotEqual";
    public const IS_REGEX = "checkRegex";

    public const CUSTOM = "checkCustom";
}
