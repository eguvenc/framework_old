<?php

namespace Tests\Authentication\Storage;

use Obullo\Authentication\Storage\AbstractTestStorage;
use Obullo\Authentication\Storage\Memcache as StorageMemcache;

class Memcache extends AbstractTestStorage
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

        $this->storage = new StorageMemcache(
            $container,
            $container->get('request'),
            $container->get('session'),
            $container->get('user.params')
        );
        $this->storage->setIdentifier('user@example.com');
        $this->setDisabledMethods(
            [
                'getBlock', // This method not exists in memcached storage.
            ]
        );
    }

    /**
     * Get valid memory segment key
     * 
     * @return void
     */
    public function getMemoryBlockKey()
    {
        $block = $this->storage->getCacheKey(). ':__temporary:' .$this->storage->getUserId();
        $this->assertEqual($block, $this->storage->getBlock('__temporary'), "I expect the block key equals to key '$block'.");
    }
}
