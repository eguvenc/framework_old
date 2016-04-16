<?php

namespace Tests\Container\ServiceProvider\Connector;

use AMQPConnection;
use RuntimeException;
use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;

class Amqp extends TestController
{
    protected $AMQPConnection;

    /**
     * Constructor
     * 
     * @param object $container container
     */
    public function __construct($container)
    {
        $this->AMQPConnection = $container->get('amqp')->shared(['connection' => 'default']);

        if (! $this->AMQPConnection instanceof AMQPConnection) {
            throw new RuntimeException("AMQP service provider is not enabled.");
        }
    }

    /**
     * Shared
     * 
     * @return void
     */
    public function shared()
    {
        $AMQPConnectionShared = $this->container->get('amqp')->shared(['connection' => 'default']);

        $this->assertInstanceOf('AMQPConnection', $this->AMQPConnection, "I expect that the value is instance of AMQPConnection.");
        $this->assertSame($this->AMQPConnection, $AMQPConnectionShared, "I expect that the two variables reference the same object.");
    }

    /**
     * Factory
     * 
     * @return void
     */
    public function factory()
    {
        $AMQPConnectionFactory = $this->container->get('amqp')->factory(
            [
                'host'  => '127.0.0.1',
                'port'  => 5672,
                'username'  => 'root',
                'password'  => '123456',
                'vhost' => '/'
            ]
        );
        $this->assertNotSame($this->AMQPConnection, $AMQPConnectionFactory, "I expect that the shared and factory instances are not the same object.");

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