<?php

namespace Tests\Authentication\Storage;

use Obullo\Authentication\Storage\AbstractTestStorage;
use Obullo\Authentication\Storage\Redis as StorageRedis;

class Redis extends AbstractTestStorage
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
}
