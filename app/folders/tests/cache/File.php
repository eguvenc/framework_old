<?php

namespace Tests\Cache;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;

class File extends TestController
{
    protected $cache;

    /**
     * Constructor
     *
     * @param object $container container
     */
    public function __construct($container)
    {
        $this->cache = $container->get('cacheManager')->shared(['driver' => 'file']);
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
     * Returns to all keys
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
        TestOutput::varDump($getAllData);
        $this->cache->removeItems(array_keys($items));
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
        $getAllData = $this->cache->getAllData();

        $this->assertEmpty($getAllData, "I expect that the value is empty.");
    }

    /**
     * Cache Info
     * 
     * @return array
     */
    public function getInfo()
    {
        $items = [
            'test1' => 'test-value1',
            'test2' => 'test-value2',
        ];
        $this->cache->setItems($items);
        $info = $this->cache->getInfo();

        foreach ($info as $splFileInfo) {
            $files[] = $splFileInfo->getFilename();
        }
        $this->assertEqual('test1', $files[0], "I expect that the name of first filename is equal to test1.");
        $this->assertEqual('test2', $files[1], "I expect that the value of second filename is equal to test2.");

        $this->cache->removeItems(array_keys($items));
    }
    
}