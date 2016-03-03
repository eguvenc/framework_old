<?php

namespace Tests\Authentication;

use Obullo\Http\TestController;
use Obullo\Authentication\Token;

class Identity extends TestController
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'tests::index',
            ['content' => $this->getClassMethods()]
        );
    }

    /**
     * Guest
     * 
     * @return void
     */
    public function guest()
    {
        $this->user->identity->logout();
        $this->assertTrue($this->user->identity->guest(), "I logout, then i refresh page and i expect value is true.");
    }

    /**
     * Check
     * 
     * @return void
     */
    public function check()
    {
        $this->login();
        $this->assertTrue($this->user->identity->check(), "I login then i expect value is true.");
    }

    /**
     * Check recaller cookie
     * 
     * @return string
     */
    public function recallerExists()
    {
        $this->user->identity->destroy();
        $params = $this->config->load('providers::user')['params'];

        $rm = 'fgvH6hrlWNDeb9jz5L2P4xBW3vdrDP17';
        $this->cookie->set((string)$params['login']['rememberMe']['cookie']['name'], $rm);

        $this->session->remove('Auth/IgnoreRecaller');
        $this->assertEqual($this->user->identity->recallerExists(), $rm, "I set a recaller cookie, then i refresh the page and i expect value is equal to $rm");
        $this->varDump($rm);
    }

    /**
     * Check auth is temporary
     * 
     * @return boolean
     */
    public function isTemporary()
    {
        if ($this->user->identity->isTemporary() == false && $this->user->identity->guest()) {
            $this->login();
            $this->user->identity->makeTemporary();
        }
        $this->assertTrue($this->user->identity->isTemporary(), "I login then i set identity as temporary, then i refresh the page and i expect value is true.");
    }

    /**
     * Expire permanent identity
     * 
     * @return string
     */
    public function expire()
    {
        $this->user->identity->destroy();
        $this->login(false);
        $this->user->identity->expire(5);
        $time = time();
        $this->assertGreaterThan($time, $this->user->identity->get('__expire'),  "I login.Then i set identity expire and i expect to __expire value is greater than $time.");
        $this->varDump($this->user->identity->getArray());
    }

    /**
     * Check identity is expired
     * 
     * @return boolean
     */
    public function isExpired()
    {
        $this->login(false);
        $this->user->identity->expire(2);
        $this->assertTrue($this->user->identity->isExpired(), "I login.Then i set identity expire.I wait 2 seconds.Then i expect value is true");
    }

    /**
     * Make temporary user
     * 
     * @return string
     */
    public function makeTemporary()
    {
        // Test : Make permanent user  usename : user@example.com, password: 123456
        //
        // Expected Result :
        // 
        // I see boolean(false) then click refresh i see boolean(true)
        
        $this->login(false);
        $this->user->identity->makeTemporary();  // Make temporary user.

        var_dump($this->user->identity->isTemporary());
    }

    /**
     * Make permanent user
     * 
     * @return string
     */
    public function makePermanent()
    {
        // Test : Make permanent user  usename : user@example.com, password: 123456
        //
        // Expected Result :
        // 
        // boolean(false)

        $this->login();
        $this->user->identity->makeTemporary();  // Make temporary user.
        $this->user->identity->makePermanent();  // Make permanent user.

        var_dump($this->user->identity->isTemporary());
    }

    /**
     * Returns to time
     * 
     * @return string(10) "1456922578" 
     */
    public function getTime()
    {
        $this->login();
        var_dump($this->user->identity->getTime());
    }

    /**
     * Returns to time
     * 
     * @return string
     */
    public function getArray()
    {
        // Test : getArray
        //
        // Expected Result :
        // Array
        // (
        //     [__isAuthenticated] => 1
        //     [__isTemporary] => 0
        //     [__rememberMe] => 0
        //     [__time] => 1456922578
        //     [id] => 56
        //     [password] => $2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.
        //     [remember_token] => PjMT9ujPcLaUIkU2oVN7uh6l0THtuXDd
        //     [username] => user@example.com
        // )

        $this->login();
        echo "<pre>".print_r($this->user->identity->getArray(), true)."</pre>";
    }

    /**
     * Get the password needs rehash array.
     *
     * @return string hashed password $2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.
     */
    public function getPasswordNeedsReHash()
    {
        $this->login();
        $this->user->identity->set('__passwordNeedsRehash', 1);
        $this->user->identity->set('password', "$2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.");

        if ($this->user->identity->getPasswordNeedsReHash()) {
            var_dump($this->user->identity->getPassword());
        }
        $this->user->identity->destroy();
    }

    /**
     * Returns to "1" user if used remember me
     *
     * @return integer(1)
     */
    public function getRememberMe()
    {
        $this->login();
        $this->user->identity->set('__rememberMe', 1);

        var_dump($this->user->identity->getRememberMe());
        $this->user->identity->destroy();
    }

    /**
     * Returns to remember token
     *
     * @return string(32) "pc2AoW7QEpHXkq4EOFZRfIYde3Ujy3dd" 
     */
    public function getRememberToken()
    {
        $this->login();
        $token = Token::getRememberToken($this->container->get('cookie'), $this->container->get('user.params'));
        $this->user->identity->set('__rememberToken', $token);

        var_dump($this->user->identity->getRememberToken());
        $this->user->identity->destroy();
    }

    /**
     * Sets authority of user to "0" don't touch to cached data
     *
     * @return string(1) "0" string(32) "Vxwo4gswHLaE1gITfRBnDsPtCzPX8Ang" 
     */
    public function logout()
    {
        $this->login();
        $this->user->identity->logout();

        var_dump($this->user->storage->getCredentials()['__isAuthenticated']);
        var_dump($this->user->identity->getRememberToken());
    }

    /**
     * Destroy all identity data
     * 
     * @return boolean(false)
     */
    public function destroy()
    {
        $this->login();
        $this->user->identity->destroy();

        var_dump($this->user->identity->exists());
    }

    /**
     * Update temporary credentials
     * 
     * @return string
     */
    public function updateTemporary()
    {
        // Test update temporary identity
        // 
        // Expected Result :
        // 
        // First i see boolean(false) then click rehresh i see string(10) "test-value" 

        $this->login(false);
        $this->user->identity->makeTemporary();
        $this->user->identity->updateTemporary('test', 'test-value', '__temporary');
        var_dump($this->user->identity->get('test'));
    }

    /**
     * Update remember token if it exists in the memory and browser header
     *
     * @return int|boolean
     */
    public function updateRememberToken()
    {
        // Test update temporary identity
        // 
        // Expected Result :
        // 
        // When i click the "Update Token" link i see a different tokens in db each time.
        
        $this->login(false);
        $this->user->identity->set('__rememberMe', 1);
        $row = $this->db->query('SELECT remember_token from users WHERE id = 1')->rowArray();

        if ($this->request->get('update')) {
            $this->user->identity->updateRememberToken();
        } 
        var_dump($row['remember_token']);
        echo $this->url->anchor("/tests/authentication/identity/updateRememberToken?update=true", "Update Token");
    }

    /**
     * Removes "__rm" cookie from user browser
     *
     * @return bool(false) 
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
     * @return string
     */
    public function validate()
    {
        // Test : username : user@example.com, password : 123456
        //
        // Expected Result :
        // 
        // If credentials is valid i see : boolean(true)  otherwise : boolean(false)

        $this->login();
        $this->user->identity->initialize();
        $i = $this->container->get('user.params')['db.identifier'];
        $p = $this->container->get('user.params')['db.password'];

        var_dump($this->user->identity->validate([$i => 'user@example.com', $p => '123456']));
    }

    /**
     * Returns to login id of user, its an unique id for each browsers.
     * 
     * @return string login ID
     */
    public function getLoginId()
    {
        var_dump($this->user->identity->getLoginId());
    }

    /**
     * Kill authority of user using auth id
     *
     * @return boolean
     */
    public function kill()
    {
        // Test : current login id
        //
        // Expected Result :
        // 
        // We destroy current login id i see: array(0) { } 

        $this->login();
        $loginId = $this->user->identity->getLoginId();
        $this->user->identity->kill($loginId);

        var_dump($this->user->storage->getCredentials());
    }

    /**
     * Do login
     *
     * @param bool $dump var_dump switch
     * 
     * @return string|array
     */
    public function login($dump = true)
    {
        if ($this->user->identity->guest()) {

            $authResult = $this->user->login->attempt(
                [
                    'db.identifier' => 'user@example.com', 
                    'db.password'   => '123456',
                ]
            );
            if ($authResult->isValid()) {
            } else {

                if ($dump)
                var_dump($authResult->getArray());
            }
        }
    }

}