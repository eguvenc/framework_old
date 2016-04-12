<?php

namespace Tests\Container\ServiceProvider\Connector;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;

class Amqp extends TestController
{
    public function test()
    {
        $this->assertEqual('test-value', 'test-value', "I expect that the value is test-value.");
    }

}