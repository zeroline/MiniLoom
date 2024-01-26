<?php

/**
 * @author Frederik Nieß <miniloom@zeroline.me>
 * @package MiniLoom
 * @subpackage Tests
 * @license MIT
 *
 * Test case for the Filter class
 */

namespace zeroline\MiniLoom\Tests\Data\Validation;

use PHPUnit\Framework\TestCase;
use stdClass;
use zeroline\MiniLoom\Data\Validation\Validator as Validator;

class ValidationTest extends TestCase
{
    private $objectToTest = null;

    const NON_EXISTENT_PROPERTY = 'nonExistentProperty';
    const A_VALID_EMAIL_ADDRESS = 'someone@somewhere.com';
    const AN_INVALID_EMAIL_ADDRESS = 'some@one@somewhere.com';
    const A_VALID_URL = 'https://www.google.com';
    const AN_INVALID_URL = 'https:www.google';

    protected function setUp(): void
    {
        $this->objectToTest = new stdClass();
        $this->objectToTest->aString = 'a string';
        $this->objectToTest->anInteger = 4711;
        $this->objectToTest->aFloat = 47.11;
        $this->objectToTest->aBoolean = true;
        $this->objectToTest->anArray = ['a', 'b', 'c'];
        $this->objectToTest->anObject = new stdClass();
        $this->objectToTest->aNull = null;
        $this->objectToTest->aDateString = '2020-01-01';
        $this->objectToTest->aDateTimeString = '2020-01-01 12:00:00';
    }

    /* EXISTENCE */

    public function testRequired()
    {
        $this->assertTrue(Validator::checkRequired($this->objectToTest->aString));
    }

    /* TYPES */

    public function testRequiredFail()
    {
        $this->assertFalse(Validator::checkRequired($this->objectToTest->aNull));
    }

    public function testIsEmail()
    {
        $this->assertTrue(Validator::checkEmail(self::A_VALID_EMAIL_ADDRESS));
    }

    public function testIsEmailFail()
    {
        $this->assertFalse(Validator::checkEmail(self::AN_INVALID_EMAIL_ADDRESS));
    }

    public function testIsUrl()
    {
        $this->assertTrue(Validator::checkUrl(self::A_VALID_URL));
    }

    public function testIsUrlFail()
    {
        $this->assertFalse(Validator::checkUrl(self::AN_INVALID_URL));
    }

    public function testIsString()
    {
        $this->assertTrue(Validator::checkString($this->objectToTest->aString));
    }

    public function testIsStringFail()
    {
        $this->assertFalse(Validator::checkString($this->objectToTest->anInteger));
    }

    public function testIsInteger()
    {
        $this->assertTrue(Validator::checkInt($this->objectToTest->anInteger));
    }

    public function testIsIntegerFail()
    {
        $this->assertFalse(Validator::checkInt($this->objectToTest->aString));
    }

    public function testIsFloat()
    {
        $this->assertTrue(Validator::checkFloat($this->objectToTest->aFloat));
    }

    public function testIsFloatFail()
    {
        $this->assertFalse(Validator::checkFloat($this->objectToTest->aString));
    }

    public function testIsBoolean()
    {
        $this->assertTrue(Validator::checkBool($this->objectToTest->aBoolean));
    }

    public function testIsBooleanFail()
    {
        $this->assertFalse(Validator::checkBool($this->objectToTest->aString));
    }

    public function testIsDate()
    {
        $this->assertTrue(Validator::checkDate($this->objectToTest->aDateString));
    }

    public function testIsDateTime()
    {
        $this->assertTrue(Validator::checkDate($this->objectToTest->aDateTimeString));
    }

    public function testIsArray()
    {
        $this->assertTrue(Validator::checkIsArray($this->objectToTest->anArray));
    }

    public function testIsArrayFail()
    {
        $this->assertFalse(Validator::checkIsArray($this->objectToTest->aString));
    }

    public function testIsObject()
    {
        $this->assertTrue(Validator::checkIsObject($this->objectToTest->anObject));
    }

    public function testIsObjectFail()
    {
        $this->assertFalse(Validator::checkIsObject($this->objectToTest->aString));
    }

    public function testIsObjectOrArray()
    {
        $this->assertTrue(Validator::checkIsObjectOrArray($this->objectToTest->anObject));
        $this->assertTrue(Validator::checkIsObjectOrArray($this->objectToTest->anArray));
    }

    public function testIsObjectOrArrayFail()
    {
        $this->assertFalse(Validator::checkIsObjectOrArray($this->objectToTest->aString));
    }

    /* NUMBER */

    public function testNumberMinValue()
    {
        $this->assertTrue(Validator::checkNumberMinValue($this->objectToTest->anInteger, 4710));
    }

    public function testNumberMinValueFail()
    {
        $this->assertFalse(Validator::checkNumberMinValue($this->objectToTest->anInteger, 4712));
    }

    public function testNumberMaxValue()
    {
        $this->assertTrue(Validator::checkNumberMaxValue($this->objectToTest->anInteger, 4712));
    }

    public function testNumberMaxValueFail()
    {
        $this->assertFalse(Validator::checkNumberMaxValue($this->objectToTest->anInteger, 4710));
    }

    public function testNumberRange()
    {
        $this->assertTrue(Validator::checkNumberRange($this->objectToTest->anInteger, 4710, 4712));
    }

    public function testNumberRangeFail()
    {
        $this->assertFalse(Validator::checkNumberRange($this->objectToTest->anInteger, 4712, 4714));
    }

    /* INTEGER */

    public function testIntMinValue()
    {
        $this->assertTrue(Validator::checkIntMinValue($this->objectToTest->anInteger, 4710));
    }

    public function testIntMinValueFail()
    {
        $this->assertFalse(Validator::checkIntMinValue($this->objectToTest->anInteger, 4712));
    }

    public function testIntMaxValue()
    {
        $this->assertTrue(Validator::checkIntMaxValue($this->objectToTest->anInteger, 4712));
    }

    public function testIntMaxValueFail()
    {
        $this->assertFalse(Validator::checkIntMaxValue($this->objectToTest->anInteger, 4710));
    }

    public function testIntRange()
    {
        $this->assertTrue(Validator::checkIntRange($this->objectToTest->anInteger, 4710, 4712));
    }

    public function testIntRangeFail()
    {
        $this->assertFalse(Validator::checkIntRange($this->objectToTest->anInteger, 4712, 4714));
    }

    /* FLOAT */

    public function testFloatMinValue()
    {
        $this->assertTrue(Validator::checkFloatMinValue($this->objectToTest->aFloat, 47.10));
    }

    public function testFloatMinValueFail()
    {
        $this->assertFalse(Validator::checkFloatMinValue($this->objectToTest->aFloat, 47.12));
    }

    public function testFloatMaxValue()
    {
        $this->assertTrue(Validator::checkFloatMaxValue($this->objectToTest->aFloat, 47.12));
    }

    public function testFloatMaxValueFail()
    {
        $this->assertFalse(Validator::checkFloatMaxValue($this->objectToTest->aFloat, 47.10));
    }

    public function testFloatRange()
    {
        $this->assertTrue(Validator::checkFloatRange($this->objectToTest->aFloat, 47.10, 47.12));
    }

    public function testFloatRangeFail()
    {
        $this->assertFalse(Validator::checkFloatRange($this->objectToTest->aFloat, 47.12, 47.14));
    }

    /* STRING */

    public function testStrMinLength()
    {
        $this->assertTrue(Validator::checkMinStrLength($this->objectToTest->aString, 8));
    }

    public function testStrMinLengthFail()
    {
        $this->assertFalse(Validator::checkMinStrLength($this->objectToTest->aString, 9));
    }

    public function testStrMaxLength()
    {
        $this->assertTrue(Validator::checkMaxStrLength($this->objectToTest->aString, 8));
    }

    public function testStrMaxLengthFail()
    {
        $this->assertFalse(Validator::checkMaxStrLength($this->objectToTest->aString, 7));
    }

    public function testStrLengthRange()
    {
        $this->assertTrue(Validator::checkStrLengthRange($this->objectToTest->aString, 8, 9));
    }

    public function testStrLengthRangeFail()
    {
        $this->assertFalse(Validator::checkStrLengthRange($this->objectToTest->aString, 9, 10));
    }

    public function testStrBeginsWith()
    {
        $this->assertTrue(Validator::checkStringBeginsWith($this->objectToTest->aString, 'a'));
    }

    public function testStrBeginsWithFail()
    {
        $this->assertFalse(Validator::checkStringBeginsWith($this->objectToTest->aString, 'b'));
    }

    public function testStrEndsWith()
    {
        $this->assertTrue(Validator::checkStringEndsWith($this->objectToTest->aString, 'g'));
    }

    public function testStrEndsWithFail()
    {
        $this->assertFalse(Validator::checkStringEndsWith($this->objectToTest->aString, 'h'));
    }

    public function testStrContains()
    {
        $this->assertTrue(Validator::checkStringContains($this->objectToTest->aString, 'str'));
    }

    public function testStrContainsFail()
    {
        $this->assertFalse(Validator::checkStringContains($this->objectToTest->aString, 'xyz'));
    }

    /* ARRAY */

    public function testInArray()
    {
        $this->assertTrue(Validator::checkInArray('a', $this->objectToTest->anArray));
    }

    public function testInArrayFail()
    {
        $this->assertFalse(Validator::checkInArray('d', $this->objectToTest->anArray));
    }

    public function testNotInArray()
    {
        $this->assertTrue(Validator::checkNotInArray('d', $this->objectToTest->anArray));
    }

    public function testNotInArrayFail()
    {
        $this->assertFalse(Validator::checkNotInArray('a', $this->objectToTest->anArray));
    }

    public function testHasCount()
    {
        $this->assertTrue(Validator::checkCount($this->objectToTest->anArray, 3));
    }

    public function testHasCountFail()
    {
        $this->assertFalse(Validator::checkCount($this->objectToTest->anArray, 4));
    }

    public function testHasCountMin()
    {
        $this->assertTrue(Validator::checkMinCount($this->objectToTest->anArray, 2));
    }

    public function testHasCountMinFail()
    {
        $this->assertFalse(Validator::checkMinCount($this->objectToTest->anArray, 4));
    }

    public function testHasCountMax()
    {
        $this->assertTrue(Validator::checkMaxCount($this->objectToTest->anArray, 4));
    }

    public function testHasCountMaxFail()
    {
        $this->assertFalse(Validator::checkMaxCount($this->objectToTest->anArray, 2));
    }

    public function testHasCountRange()
    {
        $this->assertTrue(Validator::checkCountRange($this->objectToTest->anArray, 2, 4));
    }

    public function testHasCountRangeFail()
    {
        $this->assertFalse(Validator::checkCountRange($this->objectToTest->anArray, 4, 6));
    }

    /* DATE */

    public function testDateIsBefore()
    {
        $this->assertTrue(Validator::checkDateIsBefore($this->objectToTest->aDateString, '2020-01-02'));
    }

    public function testDateIsBeforeFail()
    {
        $this->assertFalse(Validator::checkDateIsBefore($this->objectToTest->aDateString, '2019-12-31'));
    }

    public function testDateIsAfter()
    {
        $this->assertTrue(Validator::checkDateIsAfter($this->objectToTest->aDateString, '2019-12-31'));
    }

    public function testDateIsAfterFail()
    {
        $this->assertFalse(Validator::checkDateIsAfter($this->objectToTest->aDateString, '2020-01-02'));
    }

    public function testDateIsBetween()
    {
        $this->assertTrue(Validator::checkDateIsBetween($this->objectToTest->aDateString, '2019-12-31', '2020-01-02'));
    }

    public function testDateIsBetweenFail()
    {
        $this->assertFalse(Validator::checkDateIsBetween($this->objectToTest->aDateString, '2020-01-02', '2020-01-04'));
    }

    public function testDateTimeIsBefore()
    {
        $this->assertTrue(Validator::checkDateIsBefore($this->objectToTest->aDateTimeString, '2020-01-01 12:00:01'));
    }

    public function testDateTimeIsBeforeFail()
    {
        $this->assertFalse(Validator::checkDateIsBefore($this->objectToTest->aDateTimeString, '2020-01-01 11:59:59'));
    }

    public function testDateTimeIsAfter()
    {
        $this->assertTrue(Validator::checkDateIsAfter($this->objectToTest->aDateTimeString, '2020-01-01 11:59:59'));
    }

    public function testDateTimeIsAfterFail()
    {
        $this->assertFalse(Validator::checkDateIsAfter($this->objectToTest->aDateTimeString, '2020-01-01 12:00:01'));
    }

    public function testDateTimeIsBetween()
    {
        $this->assertTrue(Validator::checkDateIsBetween($this->objectToTest->aDateTimeString, '2020-01-01 11:59:59', '2020-01-01 12:00:01'));
    }

    public function testDateTimeIsBetweenFail()
    {
        $this->assertFalse(Validator::checkDateIsBetween($this->objectToTest->aDateTimeString, '2020-01-01 12:00:01', '2020-01-01 11:59:59'));
    }

    /* IBAN & BIC */

    public function testIsValidBIC()
    {
        $this->assertTrue(Validator::checkBIC('GENODEM1GLS'));
    }

    public function testIsValidBICFail()
    {
        $this->assertFalse(Validator::checkBIC('GENODEM1GL'));
    }

    public function testIsValidIban()
    {
        $this->assertTrue(Validator::checkIBAN('DE89370400440532013000'));
    }

    public function testIsValidIbanFail()
    {
        $this->assertFalse(Validator::checkIBAN('DE89370400440532013001'));
    }

    /* CONTENT */

    public function testIsValidBase64()
    {
        $this->assertTrue(Validator::checkIsBase64String('dGVzdA=='));
    }

    public function testIsValidBase64Fail()
    {
        $this->assertFalse(Validator::checkIsBase64String('dGVzdA!=='));
    }

    public function testIsValidJson()
    {
        $this->assertTrue(Validator::checkIsValidJsonString('{"a": "b"}'));
    }

    public function testIsValidJsonFail()
    {
        $this->assertFalse(Validator::checkIsValidJsonString('{"a": "b"'));
    }

    /* GENERIC */

    public function testIsEqual()
    {
        $this->assertTrue(Validator::checkEqual($this->objectToTest->aString, 'a string'));
    }

    public function testIsEqualFail()
    {
        $this->assertFalse(Validator::checkEqual($this->objectToTest->aString, 'a string '));
    }

    public function testIsNotEqual()
    {
        $this->assertTrue(Validator::checkNotEqual($this->objectToTest->aString, 'a string '));
    }

    public function testIsNotEqualFail()
    {
        $this->assertFalse(Validator::checkNotEqual($this->objectToTest->aString, 'a string'));
    }

    public function testWithRegExp()
    {
        $this->assertTrue(Validator::checkRegEx($this->objectToTest->aString, '/^a string$/'));
    }

    public function testWithRegExpFail()
    {
        $this->assertFalse(Validator::checkRegEx($this->objectToTest->aString, '/^a string $/'));
    }

    /* CUSTOM */

    public function testCustom()
    {
        $this->assertTrue(Validator::checkCustom($this->objectToTest->aString, function ($value) {
            return $value === 'a string';
        }));
    }
}
