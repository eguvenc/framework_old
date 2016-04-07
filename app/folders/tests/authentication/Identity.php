<?php

namespace Tests\Authentication;

use Obullo\Tests\TestUser;
use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;
use Obullo\Authentication\Token;

class Identity extends TestController
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
     * Guest
     * 
     * @return void
     */
    public function guest()
    {
        $user = new TestUser($this->container);
        $user->logout();
        $this->assertTrue($user->identity->guest(), "I logout, then i expect that the value is true.");
        $user->destroy();
    }

    /**
     * Check
     * 
     * @return void
     */
    public function check()
    {
        $user = new TestUser($this->container);
        $user->login();
        $this->assertTrue($user->identity->check(), "I login then i expect that the value is true.");
        $user->destroy();
    }

    /**
     * Check recaller cookie
     * 
     * @return void
     */
    public function recallerExists()
    {
        $user = new TestUser($this->container);
        $user->destroy();

        $params = $this->config->get('providers::user')['params'];

        $rm = 'fgvH6hrlWNDeb9jz5L2P4xBW3vdrDP17';
        $cookies = [
            $params['login']['rememberMe']['cookie']['name'] => $rm
        ];
        $this->session->remove('Auth/IgnoreRecaller');
        $exists = $user->identity->recallerExists($cookies);
        $this->assertEqual($exists, $rm, "I set a recaller cookie then i expect that the value is equal to '$rm'");
        $user->destroy();
    }

    /**
     * Check auth is temporary
     * 
     * @return void
     */
    public function isTemporary()
    {
        $user = new TestUser($this->container);

        if ($user->identity->isTemporary() == false && $user->identity->guest()) {
            $user->login();
            $user->identity->makeTemporary();
        }
        $this->assertTrue($user->identity->isTemporary(), "I login then i set a temporary identity and i expect that the value is true.");
        $user->identity->destroyTemporary();
        $user->destroy();
    }

    /**
     * Expire permanent identity
     * 
     * @return void
     */
    public function expire()
    {
        $user = new TestUser($this->container);
        $user->login();

        $time = time();
        $user->identity->expire(1);
        $this->assertGreaterThan($user->identity->get('__expire'), $time, "I login.Then i set identity as expired and i expect to $time is greater than __expire value.");
        $user->destroy();
    }

    /**
     * Check identity is expired
     * 
     * @return void
     */
    public function isExpired()
    {
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->expire(1);
        $expire = $user->identity->get('__expire');
        $expire = $expire - time();

        $this->assertEqual($expire, 1, "I login.Then i set identity as expired for 1 secs and i expect expire - time() that is equal to 1.");
        $user->destroy();
    }

    /**
     * Make temporary user
     * 
     * @return void
     */
    public function makeTemporary()
    {
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->makeTemporary();
        $this->assertTrue($user->identity->isTemporary(), "I login as temporary.I expect that the value is true.");

        if ($user->identity->isTemporary()) {
            $user->identity->destroyTemporary();  // Destroy temporary identity
        }
        $user->destroy();
    }

    /**
     * Make permanent user
     * 
     * @return void
     */
    public function makePermanent()
    {
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->makeTemporary();  // Make temporary user.
        $user->identity->makePermanent();  // Make permanent user.

        $this->assertFalse($user->identity->isTemporary(), "I login as temporary.Then i set it identity as permanent and i expect that the value is false.");
        $user->destroy();
    }

    /**
     * Returns to time
     * 
     * @return void
     */
    public function getTime()
    {
        $user = new TestUser($this->container);
        $user->login();

        $time = $user->identity->getTime();
        if ($this->assertInternalType('integer', $time, "I expect that the value of time is integer.")) {
            $this->assertDate($time, "I expect that the date is valid.");
        }
        $user->destroy();
    }

    /**
     * Returns to time
     * 
     * @return void
     */
    public function getArray()
    {
        $user = new TestUser($this->container);
        $user->login();

        $array = $user->identity->getArray();
        $this->assertArrayHasKey('__isAuthenticated', $array, "I expect identity array has '__isAuthenticated' key.");
        TestOutput::varDump($user->identity->getArray());
        $user->destroy();
    }

    /**
     * Get the password needs rehash array.
     *
     * @return void
     */
    public function getPasswordNeedsReHash()
    {
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->set('__passwordNeedsRehash', 1);
        $user->identity->set('password', "$2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.");

        if ($user->identity->getPasswordNeedsReHash()) {
            $this->assertEqual($user->identity->getPassword(), "$2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.", "I expect identity password that is equal to $2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.");
        }
        $user->destroy();
    }

    /**
     * Returns to "1" user if used remember me
     *
     * @return void
     */
    public function getRememberMe()
    {
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->set('__rememberMe', 1);

        $this->assertInternalType('integer', $user->identity->getRememberMe(), "I expect __rememberMe value that is an integer.");
        $this->assertEqual($user->identity->getRememberMe(), 1, "I expect __rememberMe value that is 1.");
        $user->identity->destroy();
    }

    /**
     * Returns to remember token
     *
     * @return void
     */
    public function getRememberToken()
    {
        $user = new TestUser($this->container);
        $user->login();

        $token = Token::getRememberToken($this->container->get('cookie'), $this->container->get('user.params'));
        $user->identity->set('__rememberToken', $token);
        $token = $user->identity->getRememberToken();

        $this->assertInternalType('alnum', $token, "I login.I create remember me token and i expect that the type is alfanumeric.");
        $this->assertEqual(32, strlen($token), "I expect length of value that is equal to 32.");
        $user->destroy();
    }

    /**
     * Sets authority of user to "0" don't touch to cached data
     *
     * @return void
     */
    public function logout()
    {
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->logout();
        $credentials = $this->user->storage->getCredentials();

        $this->assertArrayHasKey('__isAuthenticated', $credentials, "I expect user credentials has '__isAuthenticated' key.");
        $this->assertEqual($credentials['__isAuthenticated'], 0, "I expect value of '__isAuthenticated' that is equal to 0.");
        TestOutput::varDump($credentials);
        $user->destroy();
    }

    /**
     * Destroy permanent identity of authorized user
     * 
     * @return void
     */
    public function destroy()
    {
        $user = new TestUser($this->container);
        $user->login();
        $user->destroy();
        $this->assertFalse($user->identity->exists(), "I destroy the identiy and i expect that the value is false.");
    }

    /**
     * Update temporary credentials
     * 
     * @return void
     */
    public function updateTemporary()
    {
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->makeTemporary();
        $user->identity->updateTemporary('test', 'test-value');

        $this->assertEqual($user->identity->get('test'), "test-value", "I create temporay identiy then i update it with 'test-value' and i expect that the value is equal to it.");
        $user->identity->destroyTemporary();
        $user->destroy();
    }

    /**
     * Destroy temporary identity of unauthorized user
     * 
     * @return void
     */
    public function destroyTemporary()
    {
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->makeTemporary();
        $user->identity->destroyTemporary();
        $this->assertFalse($user->identity->exists(), "I destroy the identiy and i expect that the value is false.");
        $user->destroy();
    }

    /**
     * Update remember token if it exists in the memory and browser header
     *
     * @return void
     */
    public function updateRememberToken()
    {
        $sql ='SELECT remember_token FROM users WHERE id = 1';
        
        $user = new TestUser($this->container);
        $user->login();

        $user->identity->set('__rememberMe', 1);
        $beforeRow = $this->db->query($sql)->rowArray();
        $user->identity->updateRememberToken();
        $user->identity->set('__rememberMe', 0);

        $afterRow = $this->db->query($sql)->rowArray();
        $name = $this->container->get('user.params')['login']['rememberMe']['cookie']['name'];
        $this->cookie->delete($name);

        $this->assertNotEqual($beforeRow['remember_token'], $afterRow['remember_token'], "I check remember_token from database and i expect that the value is not equal to old value.");
        $this->assertInternalType('alnum', $afterRow['remember_token'], "I expect that the value is alfanumeric.");
        $this->assertEqual(strlen($afterRow['remember_token']), 32, "I expect length of value that is equal to 32.");
        $user->destroy();
    }

    /**
     * Removes "__rm" cookie from user browser
     *
     * @return void
     */
    public function forgetMe()
    {   
        $user = new TestUser($this->container);
        $user->identity->initialize();
        $name = $this->container->get('user.params')['login']['rememberMe']['cookie']['name'];

        $this->cookie->set($name, "test-value");
        $user->identity->forgetMe();
        $cookieID = $this->cookie->getId();
        $headers  = $this->cookie->getHeaders();

        $this->assertArrayHasKey($cookieID, $headers, "I set a test-value to cookie headers then i do forgetMe and i expect the cookie id removed from cookie headers.");
    }

    /**
     * Validate credentials authorized user credentials
     * 
     * @return void
     */
    public function validate()
    {
        $user = new TestUser($this->container);
        $user->login();

        $i = $this->container->get('user.params')['db.identifier'];
        $p = $this->container->get('user.params')['db.password'];

        $credentials = $this->config->get('tests')['login']['credentials'];

        $isValid = $user->identity->validate([$i => $credentials['username'], $p => $credentials['password']]);
        $this->assertTrue($isValid, "I login.Then i validate user credentials and i expect that the value is true.");
        $user->destroy();
    }

    /**
     * Returns to login id of user, its an unique id for each browsers.
     * 
     * @return void
     */
    public function getLoginId()
    {
        $user = new TestUser($this->container);
        $user->login();

        $loginId = $user->identity->getLoginId();  // 87010e88
        $this->assertInternalType('alnum', $loginId, "I expect that the value is alfanumeric.");
        $this->assertEqual(strlen($loginId), 32, "I expect that the length of string is 32.");
        $user->destroy();
    }

    /**
     * Kill authority of user using auth id
     *
     * @return void
     */
    public function kill()
    {
        $user = new TestUser($this->container);
        $user->login();

        $loginId = $user->identity->getLoginId();
        $user->identity->kill($loginId);
        $this->assertEmpty($this->user->storage->getCredentials(), "I login.Then i kill identity with my login id and i expect that the identity data is empty.");
        $user->destroy();
    }

}