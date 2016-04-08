<?php

namespace Tests\Captcha;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;
use Obullo\Captcha\CaptchaResult as CaptchaResultClass;

class CaptchaResult extends TestController
{
    /**
     * Check error constants & variables 
     * and isValid()
     * 
     * @return void
     */
    public function isValid()
    {
        $captchaResult = new CaptchaResultClass(0, 'test');

        $this->assertEqual(
            $captchaResult->getCode(),
            CaptchaResultClass::FAILURE,
            "I create a CaptchaResult::FAILURE and i expect that the value is 0."
        );
        $this->assertFalse($captchaResult->isValid(), "I do isValid() and i expect that the value is false.");

        $captchaResult = new CaptchaResultClass(1, 'test');
        $this->assertEqual(
            $captchaResult->getCode(),
            CaptchaResultClass::SUCCESS,
            "I create a CaptchaResult::SUCCESS and i expect that the value is 1."
        );
        $this->assertTrue($captchaResult->isValid(), "I do isValid() and i expect that the value is true.");


        $captchaResult = new CaptchaResultClass(-1, 'test');
        $this->assertEqual(
            $captchaResult->getCode(),
            CaptchaResultClass::FAILURE_EXPIRED,
            "I create a CaptchaResult::FAILURE_EXPIRED and i expect that the value is -1."
        );
        $this->assertFalse($captchaResult->isValid(), "I do isValid() and i expect that the value is false.");

        $captchaResult = new CaptchaResultClass(-2, 'test');
        $this->assertEqual(
            $captchaResult->getCode(),
            CaptchaResultClass::FAILURE_INVALID_CODE,
            "I create a CaptchaResult::FAILURE_INVALID_CODE and i expect that the value is -2."
        );
        $this->assertFalse($captchaResult->isValid(), "I do isValid() and i expect that the value is false.");

        $captchaResult = new CaptchaResultClass(-3, 'test');
        $this->assertEqual(
            $captchaResult->getCode(),
            CaptchaResultClass::FAILURE_CAPTCHA_NOT_FOUND,
            "I create a CaptchaResult::FAILURE_CAPTCHA_NOT_FOUND and i expect that the value is -3."
        );
        $this->assertFalse($captchaResult->isValid(), "I do isValid() and i expect that the value is false.");
    }

    /**
     * Get the result code for this captcha verification attempt
     *
     * @return void
     */
    public function getCode()
    {
        $captchaResult = new CaptchaResultClass(1, 'test');
        $this->assertEqual($captchaResult->getCode(), CaptchaResultClass::SUCCESS, "I expect that the value is 1.");
    }

    /**
     * Get messages
     *
     * @return void
     */
    public function getMessages()
    {
        $captchaResult = new CaptchaResultClass(0, array('General Failure'));
        $this->assertEqual($captchaResult->getMessages()[0], "General Failure", "I expect that the value is 'General Failure'.");
    }

    /**
     * Set custom error code
     *
     * @return void
     */
    public function setCode()
    {
        $captchaResult = new CaptchaResultClass(0, 'test');
        $captchaResult->setCode(1);
        $this->assertEqual($captchaResult->getCode(), 1, "I expect that the value is 1.");
    }

    /**
     * Set custom error messages
     *
     * @return void
     */
    public function setMessage()
    {
        $captchaResult = new CaptchaResultClass(0,  array('General Failure'));
        $captchaResult->setMessage("Test Message");
        $this->assertEqual($captchaResult->getMessages()[1], "Test Message", "I expect that the value is 'Test Message'.");
    }

    /**
     * Gets all messages
     * 
     * @return array
     */
    public function getArray()
    {
        $captchaResult = new CaptchaResultClass(0, array('General Failure'));
        $captchaResult->setMessage("Test Message");

        $results = $captchaResult->getArray();

        if ($this->assertArrayHasKey("code", $results, "I expect the result array has 'code' key.")) {
            $this->assertEqual(0, $results['code'], "I expect that the code value is 0.");
            $this->assertInternalType('integer', $results['code'], "I expect that the type of value is integer.");
        }
        if ($this->assertArrayHasKey("messages", $results, "I expect the result array has 'messages' key.")) {
            $this->assertEqual($results['messages'][1], "Test Message", "I expect that the message is 'Test Message'.");
        }
        TestOutput::varDump($results);
    }

}