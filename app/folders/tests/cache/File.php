<?php

namespace Tests\Cache;

use Obullo\Http\Tests\TestController;
use Obullo\Cache\Handler\File as FileCache;

class File extends TestController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cache = new FileCache(['path' => '/resources/data/cache/']);
    }

    /**
     * Get data
     * 
     * @return void
     */
    public function get()
    {
        $this->cache->set('test', 'test-value');
        $this->assertEqual($this->cache->get('test'), 'test-value', "I expect that the value is test-value.");
        $this->cache->remove('test');
    }

    /**
     * Get data
     * 
     * @return void
     */
    public function has()
    {
        $this->cache->set('test', 'test-value');
        $this->assertTrue($this->cache->has('test'), "I expect that the value is true.");
        $this->cache->remove('test');
    }
}