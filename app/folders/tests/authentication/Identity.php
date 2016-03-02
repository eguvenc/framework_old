<?php

namespace Tests\Authentication;

use Obullo\Http\Controller;
use Obullo\Authentication\Token;

class Identity extends Controller
{
    /**
     * Index (Enable annotations from config.php file !!!!)
     * 
     * @return void
     */
    public function index()
    {
        $methods = get_class_methods($this);
        foreach ($methods as $name) {
            if (! in_array($name, ['index', 'setContainer', 'getContainer', '__get','__set', 'login']))
            echo $this->url->anchor(rtrim($this->request->getRequestTarget(), "/")."/".$name, $name)."<br>";
        }
    }

    /**
     * Guest
     * 
     * @return void
     */
    public function guest()
    {
        // Test : Unauthorized user
        //
        // Expected Result :
        // 
        // If user authorized : boolean(false) otherwise : boolean(true)

        var_dump($this->user->identity->guest());
    }

    /**
     * Check
     * 
     * @return void
     */
    public function check()
    {
        // Test : Authorized user
        //
        // Expected Result :
        // 
        // If user authorized : boolean(true) otherwise : boolean(false)
        
        var_dump($this->user->identity->check());
    }

    /**
     * Check recaller cookie
     * 
     * @return string
     */
    public function recallerExists()
    {
        // Test : Unauthorized user who has a recaller cookie with : __rm  value.
        //
        // Expected Result :
        // 
        // First i see boolean(false) then i click refresh and i see __rm value: 
        // string(32) "fgvH6hrlWNDeb9jz5L2P4xBW3vdrDP17" 
        
        $this->user->identity->destroy(); //  Make user guest.

        $params = $this->config->load('providers::user')['params'];

        $this->cookie->set((string)$params['login']['rememberMe']['cookie']['name'], 'fgvH6hrlWNDeb9jz5L2P4xBW3vdrDP17');

        $this->session->remove('Auth/IgnoreRecaller');  // We use ignore recaller for this situation :
                                                        // if user has remember cookie and still try to login attempt.
                                                        // In here we remove recaller point to make sure recallerExists()
                                                        // functionality.

        var_dump($this->user->identity->recallerExists());  
    }

    /**
     * Check auth is temporary
     * 
     * @return boolean
     */
    public function isTemporary()
    {
        // Test : Authorize user and make identity temporary. usename : user@example.com, password: 123456
        //
        // Expected Result :
        // 
        // boolean(true)

        if ($this->user->identity->isTemporary() == false && $this->user->identity->guest()) {

            $this->login();
            $this->user->identity->makeTemporary();  // Make temporary user.

            return $this->response->redirect("/tests/authentication/identity/isTemporary");
        }

        var_dump($this->user->identity->isTemporary());
    }

    /**
     * Expire permanent identity
     * 
     * @return string
     */
    public function expire()
    {
        // Test : Expired user  usename : user@example.com, password: 123456
        //
        // Expected Result :
        // 
        // If user is expired after 5 seconds i see : boolean(true) otherwise i see below the output.
        
        // Array
        // (
        //     [__expire] => 1456917284
        //     [__isAuthenticated] => 1
        //     [__isTemporary] => 0
        //     [__rememberMe] => 0
        //     [__time] => 1456917280
        //     [date] => 0
        //     [id] => 56
        //     [password] => $2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.
        //     [remember_token] => PjMT9ujPcLaUIkU2oVN7uh6l0THtuXDd
        //     [username] => user@example.com
        // )

        if ($this->user->identity->isExpired()) {
            $this->user->identity->destroy();
            var_dump($this->user->identity->isExpired());
            return;
        }        
        $this->login();

        if (! $this->user->identity->has('__expire')) {
            $this->user->identity->expire(5);  // Expire in 5 seconds.

            echo "User identity will expired in (5) seconds.<br>";

        } else {

            echo "<pre>".print_r($this->user->identity->getArray(), true)."</pre>";
        }
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
     * Validate credentials without login
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

        $this->user->identity->initialize();
        $i = $this->container->get('user.params')['db.identifier'];
        $p = $this->container->get('user.params')['db.password'];

        var_dump($this->user->identity->validate([$i => 'user@example.com', $p => '123456']));
    }

    /**
     * Do login
     *
     * @param bool $dump var_dump switch
     * 
     * @return string|array
     */
    protected function login($dump = true)
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