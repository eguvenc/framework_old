<?php

namespace Tests\Container\ServiceProvider\Connector;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;

class Amqp extends TestController
{
    /**
     * Shared
     * 
     * @return void
     */
    public function shared()
    {
        $AMQPConnection = $this->container->get('amqp')->shared(['connection' => 'default']);
        $AMQPConnectionShared = $this->container->get('amqp')->shared(['connection' => 'default']);

        $this->assertInstanceOf('AMQPConnection', $AMQPConnection, "I expect that the value is instance of AMQPConnection.");
        $this->assertSame($AMQPConnection, $AMQPConnectionShared, "I expect that the two variables reference the same object.");
    }

    /**
     * Factory
     * 
     * @return void
     */
    public function factory()
    {
        $AMQPConnectionShared  = $this->container->get('amqp')->shared(['connection' => 'default']);
        $AMQPConnectionFactory = $this->container->get('amqp')->factory(
            [
                'host'  => '127.0.0.1',
                'port'  => 5672,
                'username'  => 'root',
                'password'  => '123456',
                'vhost' => '/'
            ]
        );
        $this->assertNotSame($AMQPConnectionShared, $AMQPConnectionFactory, "I expect that the shared and factory instances are not the same object.");

        $AMQPConnectionNewFactory = $this->container->get('amqp')->factory(
            [
                'host'  => '127.0.0.1',
                'port'  => 5672,
                'username'  => 'root',
                'password'  => '123456',
                'vhost' => '/',
                'test' => null  // Change something
            ]
        );
        $this->assertNotSame($AMQPConnectionFactory, $AMQPConnectionNewFactory, "I expect that the old factory and new factory instances are not the same object.");
    }

}