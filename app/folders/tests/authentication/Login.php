<?php

namespace Tests\Authentication;

use Obullo\Http\Controller;

class Login extends Controller
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
            if (! in_array($name, ['index', 'setContainer', 'getContainer', '__get','__set']))
            echo $this->url->anchor(rtrim($this->request->getRequestTarget(), "/")."/".$name, $name)."<br>";
        }
    }

    /**
     * Attempt
     * 
     * @return void
     */
    public function attempt()
    {
        // Test : username : user@example.com, password : 123456
        //
        // Expected Result :
        // 
        // Array
        // (
        //     [__isAuthenticated] => 1
        //     [__isTemporary] => 0
        //     [__isVerified] => 1
        //     [__rememberMe] => 0
        //     [__time] => 1456904436
        //     [date] => 0
        //     [id] => 56
        //     [password] => $2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.
        //     [remember_token] => Pasm2od7XTb2tf0C401G78tsQ9jvBTlV
        //     [username] => user@example.com
        // )
                
        if (! $this->request->get('logout') && ! $this->request->get('destroy')) {

            $authResult = $this->user->login->attempt(
                [
                    'db.identifier' => 'user@example.com', 
                    'db.password'   => '123456',
                ]
            );
            if ($authResult->isValid()) {

                echo $this->url->anchor("/tests/authentication/login/attempt?logout=true", "Logout")."<br>";
                echo $this->url->anchor("/tests/authentication/login/attempt?destroy=true", "Destroy")."<br>";

            } else {

                var_dump($authResult->getArray());
            }
        }

        if ($this->request->get('logout')) {
            $this->user->identity->logout();
        }
        if ($this->request->get('destroy')) {
            $this->user->identity->destroy();
        }
        echo "<pre>".print_r($this->user->identity->getArray(), true)."</pre>";
    }

    /**
     * Returns to rememberMe cookie value
     * 
     * @return boolean
     */
    public function hasRememberMe()
    {
        // Test : login with rememberMe = 1
        //
        // Expected Result :
        // 
        // I see : bool(false) 
        // When I refresh the page i see : string(32) "3Rw0woTBQXeZW6ebxn15qsSczyd9J1DI" 

        $rememberMe = 1;
        $authResult = $this->user->login->attempt(
            [
                'db.identifier' => 'user@example.com', 
                'db.password'   => '123456',
            ],
            $rememberMe
        );
        if ($authResult->isValid()) {
            
            var_dump($this->user->login->hasRememberMe());

            if ($this->user->login->hasRememberMe()) {

                $this->user->identity->destroy();
                $params = $this->config->load('providers::user')['params'];
                $this->cookie->delete($params['login']['rememberMe']['cookie']['name']);
            }

        } else {

            var_dump($authResult->getArray());
        }
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

        $this->user->identity->destroy();  // Make user not authenticated.

        $i = $this->container->get('user.params')['db.identifier'];
        $p = $this->container->get('user.params')['db.password'];

        var_dump($this->user->login->validate([$i => 'user@example.com', $p => '123456']));
    }

    /**
     * Recall user
     * 
     * @return void
     */
    public function recaller()
    {
        // Test : username : user@example.com, password : 123456, rememberMe : 1
        //
        // Expected Result :
        // 
        // Array
        // (
        //     [__isAuthenticated] => 1
        //     [__isTemporary] => 0
        //     [__isVerified] => 1
        //     [__rememberMe] => 1
        //     [__time] => 1456904436
        //     [date] => 0
        //     [id] => 56
        //     [password] => $2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.
        //     [remember_token] => Pasm2od7XTb2tf0C401G78tsQ9jvBTlV
        //     [username] => user@example.com
        // )

        if ($this->user->identity->guest()) {

            $rememberMe = 1;
            $authResult = $this->user->login->attempt(
                [
                    'db.identifier' => 'user@example.com', 
                    'db.password'   => '123456',
                ],
                $rememberMe
            );
            if ($authResult->isValid()) {

                $this->user->identity->destroy();

            } else {

                var_dump($authResult->getArray());
            }

            return $this->response->redirect("/tests/authentication/login/recaller");
        }

        if ($this->user->identity->check()) {

            echo $this->url->anchor("/tests/authentication/login/recaller?logout=true", "Logout")."<br>";
            echo $this->url->anchor("/tests/authentication/login/recaller?destroy=true", "Destroy")."<br>";
        }

        if ($this->request->get('logout')) {
            $this->user->identity->logout();
        }
        if ($this->request->get('destroy')) {
            $this->user->identity->destroy();
        }
        echo "<pre>".print_r($this->user->identity->getArray(), true)."</pre>";
    }

}