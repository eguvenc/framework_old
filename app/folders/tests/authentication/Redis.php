<?php

namespace Tests\Authentication;

use Obullo\Http\Tests\TestController;
use Obullo\Authentication\Storage\Redis as StorageRedis;

class Redis extends TestController
{
    protected $storage;

    /**
     * Constructor
     * 
     * @param object $container container
     */
    public function __construct($container)
    {
        $container->get('user');

        $this->storage = new StorageRedis(
            $container,
            $container->get('request'),
            $container->get('session'),
            $container->get('user.params')
        );
        $this->storage->setIdentifier('user@example.com');
    }

    /**
     * Returns true if temporary credentials does "not" exists
     * 
     * @return void
     */
    public function isEmpty()
    {
        $credentials = [
            'username' => 'user@example.com',
            'password' => '12346',
        ];
        $this->storage->createPermanent($credentials);
        $this->assertFalse($this->storage->isEmpty(), "I login and i expect that the value is false.");
        $this->storage->deleteCredentials();
        $this->assertTrue($this->storage->isEmpty(), "I delete user credentials and i expect that the value is true.");
    }

    /**
     * Match the user credentials.
     * 
     * @return void
     */
    public function query()
    {
        $credentials = [
            'username' => 'user@example.com',
            'password' => '12346',
        ];
        $this->storage->createPermanent($credentials);

        $result = $this->storage->query();

        $identifier = $this->container->get('user.params')['db.identifier'];
        $password   = $this->container->get('user.params')['db.password'];

        if ($this->assertArrayHasKey('__isAuthenticated', $result, "I create fake credentials i expect query array has '__isAuthenticated' key.")) {
            $this->assertEqual($result['__isAuthenticated'], 1, "I expect that the value is equal to 1.");
        }
        if ($this->assertArrayHasKey('__isTemporary', $result, "I expect identity array has '__isTemporary' key.")) {
            $this->assertEqual($result['__isTemporary'], 0, "I expect that the value is equal to 0.");
        }
        if ($this->assertArrayHasKey($identifier, $result, "I expect identity array has '$identifier' key.")) {
            $this->assertEqual($result['username'], $credentials['username'], "I expect that the value is equal to ".$credentials['username'].".");
        }
        if ($this->assertArrayHasKey($password, $result, "I expect identity array has '$password' key.")) {
            $this->assertEqual($result['password'], $credentials['password'], "I expect that the value is equal to ".$credentials['password'].".");
        }
        $this->varDump($result);
        $this->storage->deleteCredentials();
    }

    /**
     * Update credentials
     *
     * @return void
     */
    public function setCredentials()
    {
        $credentials = [
            'username' => 'user@example.com',
            'password' => '12346',
        ];
        $data = [
            '__isAuthenticated' => 1,
            '__isTemporary' => 0,
        ];
        $this->storage->setCredentials($credentials, $data, '__permanent', 60);
        $result = $this->storage->getCredentials();

        $identifier = $this->container->get('user.params')['db.identifier'];
        $password   = $this->container->get('user.params')['db.password'];

        if ($this->assertArrayHasKey('__isAuthenticated', $result, "I create fake credentials and i expect storage array has '__isAuthenticated' key.")) {
            $this->assertEqual($result['__isAuthenticated'], 1, "I expect that the value is equal to 1.");
        }
        if ($this->assertArrayHasKey('__isTemporary', $result, "I expect identity array has '__isTemporary' key.")) {
            $this->assertEqual($result['__isTemporary'], 0, "I expect that the value is equal to 0.");
        }
        if ($this->assertArrayHasKey($identifier, $result, "I expect identity array has '$identifier' key.")) {
            $this->assertEqual($result['username'], $credentials['username'], "I expect that the value is equal to ".$credentials['username'].".");
        }
        if ($this->assertArrayHasKey($password, $result, "I expect identity array has '$password' key.")) {
            $this->assertEqual($result['password'], $credentials['password'], "I expect that the value is equal to ".$credentials['password'].".");
        }
        $this->storage->deleteCredentials();
    }

    /**
     * Get user credentials data
     * 
     * @return void
     */
    public function getCredentials()
    {
        $credentials = [
            'username' => 'user@example.com',
            'password' => '12346',
        ];
        $this->storage->setCredentials($credentials, array(), '__permanent', 60);
        $result = $this->storage->getCredentials();

        $identifier = $this->container->get('user.params')['db.identifier'];
        $password   = $this->container->get('user.params')['db.password'];

        if ($this->assertArrayHasKey($identifier, $result, "I create fake credentials and i expect array has '$identifier' key.")) {
            $this->assertEqual($result['username'], $credentials['username'], "I expect that the value is equal to ".$credentials['username'].".");
        }
        if ($this->assertArrayHasKey($password, $result, "I expect array has '$password' key.")) {
            $this->assertEqual($result['password'], $credentials['password'], "I expect that the value is equal to ".$credentials['password'].".");
        }
        $this->storage->deleteCredentials();
    }

    /**
     * Deletes memory block completely
     * 
     * @return void
     */
    public function deleteCredentials()
    {
        $credentials = [
            'username' => 'user@example.com',
            'password' => '12346',
        ];
        $this->storage->setCredentials($credentials, array(), '__permanent', 60);
        $this->storage->deleteCredentials();
        $result = $this->storage->getCredentials();

        $this->assertEmpty($result, "I create fake credentials then i delete them and i expect that the value is true.");
        $this->varDump($result);
    }

    /**
     * Update data
     * 
     * @return void
     */
    public function update()
    {
        $credentials = [
            'username' => 'user@example.com',
            'password' => '12346',
        ];
        $this->storage->setCredentials($credentials, array(), '__permanent', 60);
        $this->storage->update('username', 'test@example.com');
        $result = $this->storage->getCredentials();

        if ($this->assertArrayHasKey('username', $result, "I create fake credentials then i expect array has 'username' key.")) {
            $this->assertEqual('test@example.com', $result['username'], "I update username value as 'test@example.com' and i expect that the value is equal to 'test@example.com'.");
        }
        $this->storage->deleteCredentials();
    }

    /**
     * Remove data
     * 
     * @return void
     */
    public function remove()
    {
        $credentials = [
            'username' => 'user@example.com',
            'password' => '12346',
        ];
        $this->storage->setCredentials($credentials, array(), '__permanent', 60);
        $this->storage->remove('username');
        $result = $this->storage->getCredentials();

        $this->assertArrayNotHasKey('username', $result, "I create fake credentials then i remove username key and i expect array has not 'username' key.");
        $this->storage->deleteCredentials();
    }

    /**
     * Return to all sessions of current user
     * 
     * @return array
     */
    public function getUserSessions()
    {
        $credentials = [
            'username' => 'user@example.com',
            'password' => '12346',
        ];
        $this->storage->createPermanent($credentials);
        $result  = $this->storage->getUserSessions();
        $loginId = $this->storage->getLoginId();

        if ($this->assertArrayHasKey($loginId, $result, "I create fake credentials then i expect array has '$loginId' key.")) {

            $cacheIdentifier = $result[$loginId]['key'];

            $this->assertEqual($cacheIdentifier, $this->storage->getMemoryBlockKey('__permanent'), "I expect that the value of cache identifier is equal to $cacheIdentifier.");
        }
        $this->varDump($result);
    }

}
