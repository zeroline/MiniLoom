<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @license MIT
 * @package MiniLoom
 * @subpackage Data
 *
 * The Validator class provides a set of static methods to validate data.
 */

namespace zeroline\MiniLoom\Data\Validation;

final class Validator
{
    /*****************************************************/
    /** EXISTENCE CHECK */
    /*****************************************************/

    public static function checkRequired(mixed $value): bool
    {
        if (is_numeric($value) && $value === 0) {
            return true;
        }

        return (isset($value) && !empty($value));
    }

    /*****************************************************/
    /** TYPE CHECKS */
    /*****************************************************/

    public static function checkEmail(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function checkUrl(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    public static function checkNumber(mixed $value): bool
    {
        return is_numeric($value);
    }

    public static function checkInt(mixed $value): bool
    {
        return is_int($value);
    }

    public static function checkFloat(mixed $value): bool
    {
        return is_float($value);
    }

    public static function checkBool(mixed $value): bool
    {
        return is_bool($value);
    }

    public static function checkString(mixed $value): bool
    {
        return is_string($value);
    }

    public static function checkAlpha(mixed $value): bool
    {
        return ctype_alpha($value);
    }

    public static function checkAlphaNumeric(mixed $value): bool
    {
        return ctype_alnum($value);
    }

    public static function checkDate(mixed $value): bool
    {
        return (bool)strtotime($value);
    }

    public static function checkIsArray(mixed $value): bool
    {
        return is_array($value);
    }

    public static function checkIsObject(mixed $value): bool
    {
        return is_object($value);
    }

    public static function checkIsObjectOrArray(mixed $value): bool
    {
        return (static::checkIsArray($value) ||  static::checkIsObject($value));
    }

    /*****************************************************/
    /** NUMERIC CHECKS */
    /*****************************************************/

    public static function checkNumberMinValue(mixed $value, mixed $min): bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        return ($value >= $min);
    }

    public static function checkNumberMaxValue(mixed $value, mixed $max): bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        return ($value <= $max);
    }

    public static function checkNumberRange(mixed $value, mixed $min, mixed $max): bool
    {
        if (!is_numeric($value)) {
            return false;
        }
        return ($value >= $min && $value <= $max);
    }

    public static function checkIntRange(mixed $value, int $min, int $max): bool
    {
        if (is_string($value) && !ctype_digit($value)) {
            return false;
            // contains non digit characters
        }
        if (!is_int((int) $value)) {
            return false;
            // other non-integer value or exceeds PHP_MAX_INT
        }
        return ($value >= $min && $value <= $max);
    }

    public static function checkIntMinValue(mixed $value, int $min): bool
    {
        if (is_string($value) && !ctype_digit($value)) {
            return false;
            // contains non digit characters
        }
        if (!is_int((int) $value)) {
            return false;
            // other non-integer value or exceeds PHP_MAX_INT
        }
        return ($value >= $min);
    }

    public static function checkIntMaxValue(mixed $value, int $max): bool
    {
        if (is_string($value) && !ctype_digit($value)) {
            return false;
            // contains non digit characters
        }
        if (!is_int((int) $value)) {
            return false;
            // other non-integer value or exceeds PHP_MAX_INT
        }
        return ($value <= $max);
    }

    public static function checkFloatRange(mixed $value, float $min, float $max): bool
    {
        if (!is_float($value)) {
            return false;
        }
        return ($value >= $min && $value <= $max);
    }

    public static function checkFloatMinValue(mixed $value, float $min): bool
    {
        if (!is_float($value)) {
            return false;
        }
        return ($value >= $min);
    }

    public static function checkFloatMaxValue(mixed $value, float $max): bool
    {
        if (!is_float($value)) {
            return false;
        }
        return ($value <= $max);
    }

    /*****************************************************/
    /** STRING CHECKS */
    /*****************************************************/

    public static function checkMinStrLength(mixed $value, int $minLength): bool
    {
        return strlen($value) >= $minLength;
    }

    public static function checkMaxStrLength(mixed $value, int $maxLength): bool
    {
        return strlen($value) <= $maxLength;
    }

    public static function checkStrLengthRange(mixed $value, int $minLength, int $maxLength): bool
    {
        return ( static::checkMinStrLength($value, $minLength) && static::checkMaxStrLength($value, $maxLength) );
    }

    public static function checkStringBeginsWith(mixed $value, string $needle): bool
    {
        return (bool)preg_match('/^' . preg_quote($needle, '/') . '/', $value);
    }

    public static function checkStringEndsWith(mixed $value, string $needle): bool
    {
        return (bool)preg_match('/' . preg_quote($needle, '/') . '$/', $value);
    }

    public static function checkStringContains(mixed $value, string $needle): bool
    {
        return (bool)preg_match('/' . preg_quote($needle, '/') . '/', $value);
    }

    /*****************************************************/
    /** ARRAY & OBJECT CHECKS */
    /*****************************************************/

    /**
     *
     * @param mixed $value
     * @param array<mixed> $data
     * @return bool
     */
    public static function checkInArray(mixed $value, array $data): bool
    {
        return (bool) in_array($value, $data);
    }

    /**
     *
     * @param mixed $value
     * @param array<mixed> $data
     * @return bool
     */
    public static function checkNotInArray(mixed $value, array $data): bool
    {
        return (bool) !in_array($value, $data);
    }

    public static function checkCount(mixed $value, int $count): bool
    {
        if (is_array($value)) {
            return (bool) (count($value) == $count);
        }
        return false;
    }

    public static function checkMinCount(mixed $value, int $minCount): bool
    {
        if (is_array($value)) {
            return (bool) (count($value) >= $minCount);
        }
        return false;
    }

    public static function checkMaxCount(mixed $value, int $maxCount): bool
    {
        if (is_array($value)) {
            return (bool) (count($value) <= $maxCount);
        }
        return false;
    }

    public static function checkCountRange(mixed $value, int $minCount, int $maxCount): bool
    {
        if (is_array($value)) {
            return (bool) (count($value) >= $minCount && count($value) <= $maxCount);
        }
        return false;
    }

    /*****************************************************/
    /** DATE & TIME CHECKS */
    /*****************************************************/

    public static function checkDateIsBefore(mixed $value, string $date): bool
    {
        return (bool) (strtotime($value) < strtotime($date));
    }

    public static function checkDateIsAfter(mixed $value, string $date): bool
    {
        return (bool) (strtotime($value) > strtotime($date));
    }

    public static function checkDateIsBetween(mixed $value, string $date1, string $date2): bool
    {
        return (bool) (strtotime($value) > strtotime($date1) && strtotime($value) < strtotime($date2));
    }

    /*****************************************************/
    /** SPECIAL CONTENT CHECKS */
    /*****************************************************/

    public static function checkBIC(mixed $value): bool
    {
        $bic = trim(strtolower(str_replace(' ', '', $value)));
        if (preg_match('/^[a-z]{6}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $bic)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkIBAN(mixed $value): bool
    {
        $iban = $value;
        $iban = strtolower(str_replace(' ', '', $iban));
        $Countries = array(
            'al' => 28,
            'ad' => 24,
            'at' => 20,
            'az' => 28,
            'bh' => 22,
            'be' => 16,
            'ba' => 20,
            'br' => 29,
            'bg' => 22,
            'cr' => 21,
            'hr' => 21,
            'cy' => 28,
            'cz' => 24,
            'dk' => 18,
            'do' => 28,
            'ee' => 20,
            'fo' => 18,
            'fi' => 18,
            'fr' => 27,
            'ge' => 22,
            'de' => 22,
            'gi' => 23,
            'gr' => 27,
            'gl' => 18,
            'gt' => 28,
            'hu' => 28,
            'is' => 26,
            'ie' => 22,
            'il' => 23,
            'it' => 27,
            'jo' => 30,
            'kz' => 20,
            'kw' => 30,
            'lv' => 21,
            'lb' => 28,
            'li' => 21,
            'lt' => 20,
            'lu' => 20,
            'mk' => 19,
            'mt' => 31,
            'mr' => 27,
            'mu' => 30,
            'mc' => 27,
            'md' => 24,
            'me' => 22,
            'nl' => 18,
            'no' => 15,
            'pk' => 24,
            'ps' => 29,
            'pl' => 28,
            'pt' => 25,
            'qa' => 29,
            'ro' => 24,
            'sm' => 27,
            'sa' => 24,
            'rs' => 22,
            'sk' => 24,
            'si' => 19,
            'es' => 24,
            'se' => 24,
            'ch' => 21,
            'tn' => 24,
            'tr' => 26,
            'ae' => 23,
            'gb' => 22,
            'vg' => 24);

        $Chars = array(
            'a' => 10,
            'b' => 11,
            'c' => 12,
            'd' => 13,
            'e' => 14,
            'f' => 15,
            'g' => 16,
            'h' => 17,
            'i' => 18,
            'j' => 19,
            'k' => 20,
            'l' => 21,
            'm' => 22,
            'n' => 23,
            'o' => 24,
            'p' => 25,
            'q' => 26,
            'r' => 27,
            's' => 28,
            't' => 29,
            'u' => 30,
            'v' => 31,
            'w' => 32,
            'x' => 33,
            'y' => 34,
            'z' => 35);

        if (!array_key_exists(substr($iban, 0, 2), $Countries)) {
            return false;
        }

        if (strlen($iban) == $Countries[substr($iban, 0, 2)]) {
            $MovedChar = substr($iban, 4) . substr($iban, 0, 4);
            $MovedCharArray = str_split($MovedChar);
            $NewString = "";
            foreach ($MovedCharArray as $key => $value) {
                if (!is_numeric($MovedCharArray[$key])) {
                    $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
                }
                $NewString .= $MovedCharArray[$key];
            }

            if (function_exists("bcmod")) {
                return bcmod($NewString, '97') == 1;
            } else {
                $x = $NewString;
                $y = "97";
                $take = 5;
                $mod = "";
                do {
                    $a = (int)$mod . substr($x, 0, $take);
                    $x = substr($x, $take);
                    $mod = $a % $y;
                } while (strlen($x));
                return (int)$mod == 1;
            }
        }
        return false;
    }

    public static function checkIsBase64String(string $value): bool
    {
        return (bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $value);
    }

    public static function checkIsValidJsonString(mixed $value): bool
    {
        try {
            $jsonObject = json_decode($value);
            if (is_object($jsonObject) || is_array($jsonObject)) {
                return true;
            }
        } catch (\Exception $ex) {
            return false;
        }
        return false;
    }

    /*****************************************************/
    /** GENERIC CHECKS */
    /*****************************************************/

    public static function checkEqual(mixed $value, mixed $data): bool
    {
        return (bool)($value == $data);
    }

    public static function checkNotEqual(mixed $value, mixed $data): bool
    {
        return (bool)($value != $data);
    }

    public static function checkRegEx(mixed $value, string $pattern): bool
    {
        return (bool)preg_match($pattern, $value);
    }

    /*****************************************************/
    /** CUSTOM CHECKS */
    /*****************************************************/

    public static function checkCustom(mixed $value, callable $func): bool
    {
        return (bool)call_user_func_array($func, array($value));
    }
}
