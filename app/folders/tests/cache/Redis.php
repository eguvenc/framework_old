<?php

namespace Tests\Cache;

use Obullo\Tests\TestController;

class Redis extends TestController
{
    protected $cache;

    /**
     * Constructor
     *
     * @param object $container container
     */
    public function __construct($container)
    {
        $this->cache = $container->get('cacheManager')->shared(
            [
                'driver' => 'redis',
                'connection' => 'default'
            ]
        );
    }

    /**
     * Get current serializer name
     * 
     * @return string serializer name
     */
    public function getSerializer()
    {
        $this->cache->setSerializer('php');
        $this->assertEqual('php', $this->cache->getSerializer(), "I expect that the serializer is 'php'.");
    }

    /**
     * Sets serializer
     *
     * @return void
     */
    public function setSerializer()
    {
        $this->cache->setSerializer('php');
        $this->assertEqual('php', $this->cache->getSerializer(), "I expect that the serializer is 'php'.");
    }

    /**
     * Get client option
     * 
     * @return string value
     */
    public function getOption()
    {
        $this->cache->setSerializer('php');
        $options = [
            \Redis::SERIALIZER_NONE,
            \Redis::SERIALIZER_PHP,
            2, // igbinary
        ];
        $this->assertArrayContains(
            [$this->cache->getOption('OPT_SERIALIZER')],
            $options,
            "I expect that the getOptions array has contains ".\Redis::SERIALIZER_PHP."."
        );
    }

    /**
     * Set option
     *
     * @return void
     */
    public function setOption()
    {
        $this->cache->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_NONE);
        $this->assertEqual($this->cache->_serialize("foo"), 'foo', "I set OPT_SERIALIZER as SERIALIZER_NONE and expect that the value is 'foo'.");
        $this->cache->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
        $this->assertEqual($this->cache->_serialize("foo"), 's:3:"foo";', "I set OPT_SERIALIZER as SERIALIZER_PHP and i expect that the value is 's:3:\"foo\";'.");
    }

    /**
     * Get item
     * 
     * @return void
     */
    public function getItem()
    {
        $this->cache->setItem('test', 'test-value');
        $this->assertEqual($this->cache->getItem('test'), 'test-value', "I expect that the value is test-value.");
        $this->cache->removeItem('test');
    }

    /**
     * Get items
     * 
     * @return void
     */
    public function getItems()
    {
        $items = [
            'test1' => 'test-value1',
            'test2' => 'test-value2',
        ];
        $this->cache->setItems($items);
        $values = $this->cache->getItems(['test1', 'test2']);
        
        $this->assertEqual('test-value1', $values[0], "I expect that the value is test-value1.");
        $this->assertEqual('test-value2', $values[1], "I expect that the value is test-value2.");
        $this->cache->removeItems(['test1', 'test2']);
    }

    /**
     * Get data
     * 
     * @return void
     */
    public function hasItem()
    {
        $this->cache->setItem('test', 'test-value');
        $this->assertTrue($this->cache->hasItem('test'), "I expect that the value is true.");
        $this->cache->removeItem('test');
    }

    /**
     * Get data
     * 
     * @return void
     */
    public function replaceItem()
    {
        $this->cache->setItem('test', 'test-value');
        $this->cache->replaceItem('test', 'test-value-replace');

        $this->assertEqual("test-value-replace", $this->cache->getItem('test'), "I expect that the value is equal to 'test-value-replace'.");
        $this->cache->removeItem('test');
    }
    
    /**
     * Replace data
     * 
     * @return void
     */
    public function replaceItems()
    {
        $items = [
            'test1' => 'test-value1',
            'test2' => 'test-value2',
        ];
        $this->cache->setItems($items);
        $replaceItems = [
            'test1' =>  'test-value-replace1',
            'test2' =>  'test-value-replace2',
        ];
        $this->cache->replaceItems($replaceItems);

        $this->assertEqual("test-value-replace1", $this->cache->getItem('test1'), "I expect that the value is equal to 'test-value-replace1'.");
        $this->assertEqual("test-value-replace2", $this->cache->getItem('test2'), "I expect that the value is equal to 'test-value-replace2'.");
        $this->cache->removeItems(array_keys($items));
    }

    /**
     * Set item
     * 
     * @return boolean
     */
    public function setItem()
    {
        $this->cache->setItem('test', 'test-value');
        $this->assertEqual("test-value", $this->cache->getItem('test'), "I expect that the value is equal to 'test-value-replace'.");
        $this->cache->removeItem('test');
    }

    /**
     * Set items
     *
     * @return void
     */
    public function setItems()
    {
        $items = [
            'test1' => 'test-value1',
            'test2' => 'test-value2',
        ];
        $this->cache->setItems($items);
        $this->assertEqual("test-value1", $this->cache->getItem('test1'), "I expect that the value is equal to 'test-value-replace1'.");
        $this->assertEqual("test-value2", $this->cache->getItem('test2'), "I expect that the value is equal to 'test-value-replace2'.");
        $this->cache->removeItems(array_keys($items));
    }

    /**
     * Remove item
     * 
     * @return boolean
     */
    public function removeItem()
    {
        $this->cache->setItem('test', 'test-value');
        $this->assertEqual("test-value", $this->cache->getItem('test'), "I expect that the value is equal to 'test-value-replace'.");
        $this->cache->removeItem('test');
    }

    /**
     * Remove item
     * 
     * @return boolean
     */
    public function removeItems()
    {
        $items = [
            'test1' => 'test-value1',
            'test2' => 'test-value2',
        ];
        $this->cache->setItems($items);
        $this->cache->removeItems(array_keys($items));
        $this->assertFalse($this->cache->getItem('test1'), "I expect that the value is false.");
        $this->assertFalse($this->cache->getItem('test2'), "I expect that the value is false.");
    }

    /**
     * Get all keys
     * 
     * @return array
     */
    public function getAllKeys()
    {
        $items = [
            'test1' => 'test-value1',
            'test2' => 'test-value2',
        ];
        $this->cache->setItems($items);
        $getAllKeys = $this->cache->getAllKeys();
        $this->assertArrayContains(['test1'], $getAllKeys, "I expect that the keys contain test1 key.");
        $this->assertArrayContains(['test2'], $getAllKeys, "I expect that the keys contain test2 key.");
        $this->cache->removeItems(array_keys($items));
    }

    /**
     * Returns to all keys
     * 
     * @return array
     */
    public function getAllData()
    {
        $items = [
            'test1' => 'test-value1',
            'test2' => 'test-value2',
        ];
        $this->cache->setItems($items);
        $getAllData = $this->cache->getAllData();

        $this->assertArrayContains($items, $getAllData, "I expect that the all data contain items.");
        $this->cache->removeItems(array_keys($items));
    }

    /**
     * Adds a value to the hash stored at key. If this value is already in the hash, FALSE is returned.
     * 
     * @return void
     */
    public function hSet()
    {
        $this->cache->hDel('test_', 'key1');

        $this->assertEqual($this->cache->hSet('test_', 'key1', 'hello'), 1, "I expect that the value is 1.");
        $this->assertEqual($this->cache->hGet('test_', 'key1'), 'hello', "I expect that the value is equal to 'hello'.");
    }

    /**
     * Fills in a whole hash. Non-string values are converted to string, using the standard (string) cast. NULL values are stored as empty strings.
     * 
     * @return void
     */
    public function hMSet()
    {
        $this->cache->removeItem('user:1');
        $this->cache->hMSet('user:1', array('name' => 'test', 'value' => 2000));
        $this->cache->hIncrBy('user:1', 'value', 100);
        $this->assertEqual($this->cache->hMGet('user:1', ['value'])['value'], 2100, "I expect that the value is equal to '2100'");
    }

    /**
     * Clean all data
     * 
     * @return void
     */
    public function flushAll()
    {
        $items = [
            'test1' => 'test-value1',
            'test2' => 'test-value2',
        ];
        $this->cache->setItems($items);
        $this->cache->flushAll();

        $values = $this->cache->getItems(array('test1', 'test2'));

        $this->assertEmpty($values[0], "I expect that the value is empty.");
        $this->assertEmpty($values[1], "I expect that the value is empty.");
    }

    /**
     * Cache Info
     * 
     * @return array
     */
    public function getInfo()
    {
        $this->assertNotEmpty($this->cache->getInfo(), "I expect that the info data is not empty.");
    }

}