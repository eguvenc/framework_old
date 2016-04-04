<?php

namespace Tests\Authentication;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;
use Obullo\Authentication\AuthResult as AuthResultClass;

class AuthResult extends TestController
{
    /**
     * Check error constants & variables 
     * and isValid()
     * 
     * @return boolean [description]
     */
    public function isValid()
    {
        $authResult = new AuthResultClass(0, 'test');
        $this->assertEqual(
            $authResult->getCode(),
            AuthResultClass::FAILURE,
            "I create a AuthResult::FAILURE and i expect that the value is 0."
        );
        $this->assertFalse($authResult->isValid(), "I do isValid() and i expect that the value is false.");

        $authResult = new AuthResultClass(-1, 'test');
        $this->assertEqual(
            $authResult->getCode(),
            AuthResultClass::FAILURE_IDENTITY_AMBIGUOUS,
            "I create a AuthResult::FAILURE_IDENTITY_AMBIGUOUS and i expect that the value is -1."
        );
        $this->assertFalse($authResult->isValid(), "I do isValid() and i expect that the value is false.");

        $authResult = new AuthResultClass(-2, 'test');
        $this->assertEqual(
            $authResult->getCode(),
            AuthResultClass::FAILURE_CREDENTIAL_INVALID,
            "I create a AuthResult::FAILURE_CREDENTIAL_INVALID and i expect that the value is -2."
        );
        $this->assertFalse($authResult->isValid(), "I do isValid() and i expect that the value is false.");

        $authResult = new AuthResultClass(-3, 'test');
        $this->assertEqual(
            $authResult->getCode(),
            AuthResultClass::TEMPORARY_AUTH,
            "I create a AuthResult::TEMPORARY_AUTH and i expect that the value is -3."
        );
        $this->assertFalse($authResult->isValid(), "I do isValid() and i expect that the value is false.");

        $authResult = new AuthResultClass(1, 'test');
        $this->assertEqual(
            $authResult->getCode(),
            AuthResultClass::SUCCESS,
            "I create a AuthResult::SUCCESS and i expect that the value is 1."
        );
        $this->assertTrue($authResult->isValid(), "I do isValid() and i expect that the value is true.");
    }

    /**
     * Get the result code for this authentication attempt
     *
     * @return void
     */
    public function getCode()
    {
        $authResult = new AuthResultClass(1, 'test');
        $this->assertEqual($authResult->getCode(), AuthResultClass::SUCCESS, "I expect that the value is 1.");
    }

    /**
     * Returns the identity used in the authentication attempt
     *
     * @return void
     */
    public function getIdentifier()
    {
        $authResult = new AuthResultClass(0, 'user@example.com');
        $this->assertEqual($authResult->getIdentifier(), "user@example.com", "I expect that the value is 'user@example.com'.");
    }

    /**
     * Returns an array of string reasons why the authentication attempt was unsuccessful
     *
     * If authentication was successful, this method returns an empty array.
     *
     * @return void
     */
    public function getMessages()
    {
        $authResult = new AuthResultClass(0, 'user@example.com', array('General Failure'));
        $this->assertEqual($authResult->getMessages()[0], "General Failure", "I expect that the value is 'General Failure'.");
    }

    /**
     * Set custom error code
     *
     * @return void
     */
    public function setCode()
    {
        $authResult = new AuthResultClass(0, 'user@example.com');
        $authResult->setCode(1);
        $this->assertEqual($authResult->getCode(), 1, "I expect that the value is 1.");
    }

    /**
     * Set custom error messages
     *
     * @return void
     */
    public function setMessage()
    {
        $authResult = new AuthResultClass(0, 'user@example.com', array('General Failure'));
        $authResult->setMessage("Test Message");
        $this->assertEqual($authResult->getMessages()[1], "Test Message", "I expect that the value is 'Test Message'.");
    }

    /**
     * Gets all messages
     * 
     * @return array
     */
    public function getArray()
    {
        $authResult = new AuthResultClass(0, 'user@example.com', array('General Failure'));
        $authResult->setMessage("Test Message");

        $results = $authResult->getArray();

        if ($this->assertArrayHasKey("code", $results, "I expect the result array has 'code' key.")) {
            $this->assertEqual(0, $results['code'], "I expect that the code value is 0.");
            $this->assertInternalType('integer', $results['code'], "I expect that the type of code value is Integer.");
        }
        if ($this->assertArrayHasKey("messages", $results, "I expect the result array has 'messages' key.")) {
            $this->assertEqual($results['messages'][1], "Test Message", "I expect that the message is 'Test Message'.");
        }
        if ($this->assertArrayHasKey("identifier", $results, "I expect that the 'messages' array has identifier key.")) {
            $this->assertEqual($results['identifier'], "user@example.com", "I expect that the identifier value is 'user@example.com'.");
        }
        TestOutput::varDump($results);
    }

    /**
     * Sets successful login database result row
     *
     * @return void
     */
    public function setResultRow()
    {
        $authResult = new AuthResultClass(0, 'user@example.com', array('General Failure'));
        $data = [
                    'id' => '94859845',
                    'username' => 'username',
                    'password' => null,
                    'email' => 'eguvenc@gmail.com',
                ];
        $row = (object)$data;
        $authResult->setResultRow($row);
        $resultRow = $authResult->getResultRow();

        $this->assertInstanceOf('stdClass', $resultRow, "I expect that value is instance of stdClass.");
    }

    /**
     * Returns to successful login database result row
     * 
     * @return mixed
     */
    public function getResultRow()
    {
        $authResult = new AuthResultClass(0, 'user@example.com', array('General Failure'));
        $data = [
                    'id' => '94859845',
                    'username' => 'username',
                ];
        $row = (object)$data;
        $authResult->setResultRow($row);
        $resultRow = $authResult->getResultRow();

        $this->assertObjectHasAttribute('id', $resultRow, "I expect that the object has ->id property.");
        $this->assertObjectHasAttribute('username', $resultRow, "I expect that the object has ->id property.");
    }

}