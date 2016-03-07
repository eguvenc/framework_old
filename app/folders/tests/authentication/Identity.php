<?php

namespace Tests\Authentication;

use Obullo\Http\Tests\LoginTrait;
use Obullo\Http\Tests\TestController;
use Obullo\Authentication\Token;

class Identity extends TestController
{
    use LoginTrait;

    /**
     * Guest
     * 
     * @return void
     */
    public function guest()
    {
        $this->user->identity->logout();
        $this->user->identity->initialize();
        $this->assertTrue($this->user->identity->guest(), "I logout, then i expect that the value is true.");
    }

    /**
     * Check
     * 
     * @return void
     */
    public function check()
    {
        $this->newLoginRequest();
        $this->assertTrue($this->user->identity->check(), "I login then i expect that the value is true.");
    }

    /**
     * Check recaller cookie
     * 
     * @return void
     */
    public function recallerExists()
    {
        $this->user->identity->destroy();
        $params = $this->config->load('providers::user')['params'];

        $rm = 'fgvH6hrlWNDeb9jz5L2P4xBW3vdrDP17';
        $cookies = [
            $params['login']['rememberMe']['cookie']['name'] => $rm
        ];
        $this->session->remove('Auth/IgnoreRecaller');
        $this->assertEqual($this->user->identity->recallerExists($cookies), $rm, "I set a recaller cookie, then i refresh the page and i expect that the value is equal to $rm");
        $this->varDump($rm);
    }

    /**
     * Check auth is temporary
     * 
     * @return void
     */
    public function isTemporary()
    {
        if ($this->user->identity->isTemporary() == false && $this->user->identity->guest()) {
            $this->newLoginRequest();
            $this->user->identity->makeTemporary();
            $this->user->identity->initialize();
        }
        $this->assertTrue($this->user->identity->isTemporary(), "I login then i set a temporary identity and i expect that the value is true.");
    }

    /**
     * Expire permanent identity
     * 
     * @return void
     */
    public function expire()
    {
        $this->newLoginRequest();
        $time = time();
        $this->user->identity->expire(1);
        $this->user->identity->initialize();
        $this->assertGreaterThan($this->user->identity->get('__expire'), $time, "I login.Then i set identity as expired and i expect to $time is greater than __expire value.");
        $this->user->identity->destroy();
    }

    /**
     * Check identity is expired
     * 
     * @return void
     */
    public function isExpired()
    {
        $this->newLoginRequest();
        $this->user->identity->expire(1);
        $this->user->identity->initialize();
        $expire = $this->user->identity->get('__expire');
        $expire = $expire - time();

        $this->assertEqual($expire, 1, "I login.Then i set identity as expired for 1 secs and i expect expire - time() that is equal to 1.");
    }

    /**
     * Make temporary user
     * 
     * @return void
     */
    public function makeTemporary()
    {
        $this->newLoginRequest();
        $this->user->identity->makeTemporary();
        $this->user->identity->initialize();
        $this->assertTrue($this->user->identity->isTemporary(), "I login.Then i set identity as temporary.I expect that the value is true.");

        if ($this->user->identity->isTemporary()) {
            $this->user->identity->destroyTemporary();  // Destroy temporary identity
        }
    }

    /**
     * Make permanent user
     * 
     * @return void
     */
    public function makePermanent()
    {
        $this->newLoginRequest();
        $this->user->identity->makeTemporary();  // Make temporary user.
        $this->user->identity->makePermanent();  // Make permanent user.
        $this->user->identity->initialize();

        $this->assertFalse($this->user->identity->isTemporary(), "I login.Then i set identity as temporary.Then set it as permanent and i expect that the value is false.");
    }

    /**
     * Returns to time
     * 
     * @return void
     */
    public function getTime()
    {
        $this->newLoginRequest();
        $time = $this->user->identity->getTime();
        $this->assertUnixTimeStamp($time, "I expect that the value is unix timestamp.");
    }

    /**
     * Returns to time
     * 
     * @return void
     */
    public function getArray()
    {
        $this->newLoginRequest();
        $array = $this->user->identity->getArray();
        $this->assertArrayHasKey('__isAuthenticated', $array, "I expect identity array has '__isAuthenticated' key.");
        $this->varDump($this->user->identity->getArray());
    }

    /**
     * Get the password needs rehash array.
     *
     * @return void
     */
    public function getPasswordNeedsReHash()
    {
        $this->newLoginRequest();
        $this->user->identity->set('__passwordNeedsRehash', 1);
        $this->user->identity->set('password', "$2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.");

        if ($this->user->identity->getPasswordNeedsReHash()) {
            $this->assertEqual($this->user->identity->getPassword(), "$2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.", "I expect identity password that is equal to $2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.");
        }
        $this->user->identity->destroy();
    }

    /**
     * Returns to "1" user if used remember me
     *
     * @return void
     */
    public function getRememberMe()
    {
        $this->newLoginRequest();
        $this->user->identity->set('__rememberMe', 1);

        $this->assertInternalType('integer', $this->user->identity->getRememberMe(), "I expect __rememberMe value that is an integer.");
        $this->assertEqual($this->user->identity->getRememberMe(), 1, "I expect __rememberMe value that is 1.");
        $this->user->identity->destroy();
    }

    /**
     * Returns to remember token
     *
     * @return void
     */
    public function getRememberToken()
    {
        $this->newLoginRequest();
        $token = Token::getRememberToken($this->container->get('cookie'), $this->container->get('user.params'));
        $this->user->identity->set('__rememberToken', $token);
        $token = $this->user->identity->getRememberToken();

        $this->assertInternalType('alnum', $token, "I login.I create remember me token and i expect that the type is alfanumeric.");
        $this->assertEqual(32, strlen($token), "I expect length of value that is equal to 32.");
        $this->user->identity->destroy();
    }

    /**
     * Sets authority of user to "0" don't touch to cached data
     *
     * @return void
     */
    public function logout()
    {
        $this->newLoginRequest();
        $this->user->identity->logout();
        $credentials = $this->user->storage->getCredentials();

        $this->assertArrayHasKey('__isAuthenticated', $credentials, "I expect user credentials has '__isAuthenticated' key.");
        $this->assertEqual($credentials['__isAuthenticated'], 0, "I expect value of '__isAuthenticated' that is equal to 0.");
        $this->varDump($credentials);
        $this->user->identity->destroy();
    }

    /**
     * Destroy permanent identity of authorized user
     * 
     * @return void
     */
    public function destroy()
    {
        $this->newLoginRequest();
        $this->user->identity->destroy();
        $this->user->identity->initialize();
        $this->assertFalse($this->user->identity->exists(), "I destroy the identiy and i expect that the value is false.");
    }

    /**
     * Update temporary credentials
     * 
     * @return void
     */
    public function updateTemporary()
    {
        $this->newLoginRequest();
        $this->user->identity->makeTemporary();
        $this->user->identity->initialize();
        $this->user->identity->updateTemporary('test', 'test-value');
        $this->user->identity->initialize();

        $this->assertEqual($this->user->identity->get('test'), "test-value", "I create temporay identiy then i update it with 'test-value' and i expect that the value is equal to it.");
        $this->user->identity->destroyTemporary();
    }

    /**
     * Destroy temporary identity of unauthorized user
     * 
     * @return void
     */
    public function destroyTemporary()
    {
        $this->newLoginRequest();
        $this->user->identity->makeTemporary();
        $this->user->identity->initialize();
        $this->user->identity->destroyTemporary();
        $this->user->identity->initialize();
        $exists = $this->user->identity->exists();
        $this->assertFalse($exists, "I destroy the identiy and i expect that the value is false.");
    }

    /**
     * Update remember token if it exists in the memory and browser header
     *
     * @return void
     */
    public function updateRememberToken()
    {
        $sql ='SELECT remember_token FROM users WHERE id = 1';

        $this->newLoginRequest();
        $this->user->identity->set('__rememberMe', 1);
        $beforeRow = $this->db->query($sql)->rowArray();
        $this->user->identity->updateRememberToken();
        $this->user->identity->set('__rememberMe', 0);
        $this->user->identity->initialize();
        $afterRow  = $this->db->query($sql)->rowArray();
        $name = $this->container->get('user.params')['login']['rememberMe']['cookie']['name'];
        $this->cookie->delete($name);
        $this->assertNotEqual($beforeRow['remember_token'], $afterRow['remember_token'], "I check remember_token from database and i expect that the value is not equal to old value.");
        $this->assertInternalType('alnum', $afterRow['remember_token'], "I expect that the value is alfanumeric.");
        $this->assertEqual(strlen($afterRow['remember_token']), 32, "I expect length of value that is equal to 32.");
        $this->user->identity->destroy();
    }

    /**
     * Removes "__rm" cookie from user browser
     *
     * @return void
     */
    public function forgetMe()
    {
        $this->user->identity->initialize();
        $name = $this->container->get('user.params')['login']['rememberMe']['cookie']['name'];

        $this->cookie->set($name, "test-value");
        $this->user->identity->forgetMe();
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
        $this->newLoginRequest();
        $this->user->identity->initialize();
        $i = $this->container->get('user.params')['db.identifier'];
        $p = $this->container->get('user.params')['db.password'];

        $credentials = $this->config->load('tests')['login']['credentials'];

        $isValid = $this->user->identity->validate([$i => $credentials['username'], $p => $credentials['password']]);
        $this->assertTrue($isValid, "I login.Then i validate user credentials and i expect that the value is true.");
        $this->user->identity->destroy();
    }

    /**
     * Returns to login id of user, its an unique id for each browsers.
     * 
     * @return void
     */
    public function getLoginId()
    {
        $this->newLoginRequest();
        $loginId = $this->user->identity->getLoginId();  // 87010e88
        $this->assertInternalType('alnum', $loginId, "I expect that the value is alfanumeric.");
        $this->assertEqual(strlen($loginId), 8, "I expect that the length of string is 8.");
        $this->user->identity->destroy();
    }

    /**
     * Kill authority of user using auth id
     *
     * @return void
     */
    public function kill()
    {
        $this->newLoginRequest();
        $loginId = $this->user->identity->getLoginId();
        $this->user->identity->kill($loginId);
        $this->assertEmpty($this->user->storage->getCredentials(), "I login.Then i kill identity with my login id and i expect that the identity data is empty.");
    }

}