<?php

namespace Tests\Authentication;

use Obullo\Http\Tests\LoginTrait;
use Obullo\Http\Tests\TestController;

class Login extends TestController
{
    use LoginTrait;

    /**
     * Attempt
     * 
     * @return void
     */
    public function attempt()
    {
        $this->newLoginRequest();
        $result = $this->user->identity->getArray();
        $identifier = $this->container->get('user.params')['db.identifier'];
        $password   = $this->container->get('user.params')['db.password'];
        $this->user->identity->destroy();
        
        if ($this->assertArrayHasKey('__isAuthenticated', $result, "I expect identity array has '__isAuthenticated' key.")) {
            $this->assertEqual($result['__isAuthenticated'], 1, "I expect that the value is equal to 1.");
        }
        if ($this->assertArrayHasKey('__isTemporary', $result, "I expect identity array has '__isTemporary' key.")) {
            $this->assertEqual($result['__isTemporary'], 0, "I expect that the value is equal to 0.");
        }
        $this->assertArrayHasKey('__rememberMe', $result, "I expect identity array has '__rememberMe' key.");
        $this->assertArrayHasKey('__time', $result, "I expect identity array has '__time' key.");
        $this->assertArrayHasKey($identifier, $result, "I expect identity array has '$identifier' key.");
        $this->assertArrayHasKey($password, $result, "I expect identity array has '$password' key.");
        $this->varDump($result);
    }

    /**
     * Returns to rememberMe cookie value
     * 
     * @return boolean
     */
    public function hasRememberMe()
    {
        $this->newLoginRequest(1);
        $this->assertEqual($this->user->identity->getRememberMe(), 1, "I expect that the value is 1.");
        $this->user->identity->destroy();
    }

    /**
     * Validate credentials without login
     * 
     * @return string
     */
    public function validate()
    {
        $this->user->identity->destroy();  // Make user is not authenticated.

        $i = $this->container->get('user.params')['db.identifier'];
        $p = $this->container->get('user.params')['db.password'];

        $credentials = $this->config->load('tests')['login']['credentials'];
        $isValid     = $this->user->login->validate([$i => $credentials['username'], $p => $credentials['password']]);

        $this->assertTrue($isValid, "I validate user credentials without login and i expect that the value is true.");
    }

}