<?php

namespace Tests\Authentication;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;
use Obullo\Authentication\Recaller as AuthRecaller;

class Recaller extends TestController
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
     * Recall user identity using remember token
     * 
     * @return void
     */
    public function recallUser()
    {
        $this->user->identity->destroy();

        $sql = 'SELECT remember_token FROM users WHERE id = 1';
        $row = $this->db->query($sql)->rowArray();
        $token = $row['remember_token'];

        $recaller = new AuthRecaller(
            $this->container,
            $this->container->get('auth.storage'),
            $this->container->get('auth.model'),
            $this->user->identity,
            $this->container->get('user.params')
        );
        $recaller->recallUser($token);
        $this->user->identity->initialize();
        $result = $this->user->identity->getArray();

        $identifier = $this->container->get('user.params')['db.identifier'];
        $password   = $this->container->get('user.params')['db.password'];

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
        
        $rm = $this->container->get('user.params')['login']['rememberMe']['cookie']['name'];
        $this->user->identity->destroy();
        $this->session->destroy();
        $this->cookie->delete($rm);

    }
}