<?php

namespace Widgets\Tutorials;

Class Memcache extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['view'];
        $this->c['user'];
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        // $this->cache->set('test:value', array('a' => array('b', 'c', 'd')));

        // $key = $this->c['auth.params']['cache.key'].':'.$block.':Authorized:'.$this->storage->getUserId();

        // $this->cache->delete('Auth:__permanent:Authorized:user@example.com');
        // print_r($this->cache->get('Auth:__permanent:Authorized:user@example.com'));

        $allkeys = $this->c['auth.storage']->getAllSessions();

        print_r($allkeys);

        // foreach ($allkeys as $key => $value) {
        //     print_r($key);
        // }

        // print_r($allkeys);

    }
}