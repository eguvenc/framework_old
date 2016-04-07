<?php

namespace Tests\Authentication;

use Obullo\Tests\TestUser;
use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;

class Login extends TestController
{
    /**
     * Constructor
     * 
     * @param object $container container
     */
    public function __construct($container)
    {
        $container->get("user");
    }

    /**
     * Attempt
     * 
     * @return void
     */
    public function attempt()
    {
        $user = new TestUser($this->container);
        $user->login();

        $result = $this->user->identity->getArray();
        $identifier = $this->container->get('user.params')['db.identifier'];
        $password   = $this->container->get('user.params')['db.password'];
        $user->destroy();
        
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
        
        TestOutput::varDump($result);

    }

    /**
     * Returns to rememberMe cookie value
     * 
     * @return boolean
     */
    public function hasRememberMe()
    {
        $user = new TestUser($this->container);
        $user->login(['rememberMe' => 1]);
        
        $this->assertEqual($this->user->identity->getRememberMe(), 1, "I expect that the value is 1.");
        $user->destroy();
        $this->user->identity->forgetMe();
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

        $credentials = $this->config->get('tests')['login']['credentials'];
        $isValid     = $this->user->login->validate([$i => $credentials['username'], $p => $credentials['password']]);
        $this->assertTrue($isValid, "I validate user credentials without login and i expect that the value is true.");
        $this->session->destroy();
    }

}