<?php

namespace Tests\Authentication;

use Obullo\Http\Tests\LoginTrait;
use Obullo\Http\Tests\TestController;
use Obullo\Authentication\Token;

class Identity extends TestController {

    use LoginTrait;

    /**
     * Guest
     * 
     * @return void
     */
    public function guest()
    {
        $this->user->identity->logout();
        $this->assertTrue($this->user->identity->guest(), "I logout, then i refresh page and i expect that the value is true.");
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
        $this->cookie->set((string)$params['login']['rememberMe']['cookie']['name'], $rm);

        $this->session->remove('Auth/IgnoreRecaller');
        $this->assertEqual($this->user->identity->recallerExists(), $rm, "I set a recaller cookie, then i refresh the page and i expect that the value is equal to $rm");
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
        $this->user->identity->destroy('__temporary');
        $this->user->identity->destroy('__permanent');
        $this->newLoginRequest();
        $this->user->identity->expire(2);
        $time = time();
        $this->assertGreaterThan($time, $this->user->identity->get('__expire'),  "I login.Then i set identity as expired and i expect to __expire value is greater than $time.");
    }

    /**
     * Check identity is expired
     * 
     * @return void
     */
    public function isExpired()
    {
        $this->newLoginRequest();
        $this->user->identity->expire(2);
        $this->assertTrue($this->user->identity->isExpired(), "I login.Then i set identity as expired.I wait 2 seconds.Then i expect that the value is true.");
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
            $this->user->identity->destroy('__temporary');  // Destroy temporary identity
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

        $this->assertFalse($this->user->identity->isTemporary(), "I login.Then i set identity as temporary.Then set it as permanent.Then i refresh the page and i expect that the value is false.");
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

        $this->assertType('string', $time, "I expect that the value is string.");
        $this->assertType('numeric', $time, "I expect that the value is numeric.");
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
        $this->assertHas('__isAuthenticated', $array, "I expect identity array has '__isAuthenticated' key.");
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
        $this->assertType('integer', $this->user->identity->getRememberMe(), "I expect __rememberMe value that is an integer.");
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

        $this->assertType('alnum', $token, "I login.I create remember me token and i expect that the type is alfanumeric.");
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

        var_dump($this->user->storage->getCredentials()['__isAuthenticated']);
        var_dump($this->user->identity->getRememberToken());
    }

    /**
     * Destroy all identity data
     * 
     * @return void
     */
    public function destroy()
    {
        $this->newLoginRequest();
        $this->user->identity->destroy();

        var_dump($this->user->identity->exists());
    }

    /**
     * Update temporary credentials
     * 
     * @return void
     */
    public function updateTemporary()
    {
        // Test update temporary identity
        // 
        // Expected Result :
        // 
        // First i see boolean(false) then click rehresh i see string(10) "test-value" 

        $this->newLoginRequest();
        $this->user->identity->makeTemporary();
        $this->user->identity->updateTemporary('test', 'test-value', '__temporary');
        var_dump($this->user->identity->get('test'));
    }

    /**
     * Update remember token if it exists in the memory and browser header
     *
     * @return void
     */
    public function updateRememberToken()
    {
        // Test update temporary identity
        // 
        // Expected Result :
        // 
        // When i click the "Update Token" link i see a different tokens in db each time.
        
        $this->newLoginRequest();
        $this->user->identity->set('__rememberMe', 1);
        $row = $this->db->query('SELECT remember_token FROM users WHERE id = 1')->rowArray();

        if ($this->request->get('update')) {
            $this->user->identity->updateRememberToken();
        } 
        var_dump($row['remember_token']);
        echo $this->url->anchor("/tests/authentication/identity/updateRememberToken?update=true", "Update Token");
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

        var_dump($this->cookie->get($name));
    }

    /**
     * Validate credentials authorized user credentials
     * 
     * @return void
     */
    public function validate()
    {
        // Test : username : user@example.com, password : 123456
        //
        // Expected Result :
        // 
        // If credentials is valid i see : boolean(true)  otherwise : boolean(false)

        $this->newLoginRequest();
        $this->user->identity->initialize();
        $i = $this->container->get('user.params')['db.identifier'];
        $p = $this->container->get('user.params')['db.password'];

        var_dump($this->user->identity->validate([$i => 'user@example.com', $p => '123456']));
    }

    /**
     * Returns to login id of user, its an unique id for each browsers.
     * 
     * @return void
     */
    public function getLoginId()
    {
        var_dump($this->user->identity->getLoginId());
    }

    /**
     * Kill authority of user using auth id
     *
     * @return void
     */
    public function kill()
    {
        // Test : current login id
        //
        // Expected Result :
        // 
        // We destroy current login id i see: array(0) { } 

        $this->newLoginRequest();
        $loginId = $this->user->identity->getLoginId();
        $this->user->identity->kill($loginId);

        var_dump($this->user->storage->getCredentials());
    }

}