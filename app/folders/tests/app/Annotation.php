<?php

namespace Tests\App;

use Obullo\Tests\TestController;
use Obullo\Tests\TestOutput;

class Annotation extends TestController
{
    /**
     * Annotation parser
     * 
     * @var object
     */
    protected $parser;

    /**
     * Build reflection class
     * 
     * @param object $container container
     */
    public function __construct($container)
    {
        if ($container->get('config')['extra']['annotations'] == true) {
            throw new \RuntimeException("Annotations must be disabled from your config file.");
        }
        $reflector = new \ReflectionClass($this);
        $controller = new \Obullo\Application\Annotations\Controller;
        $controller->setContainer($container);
        $controller->setReflectionClass($reflector);
        
        $this->parser = $controller;
    }

    /**
     * Index
     *
     * @middleware->method('put', 'delete')
     * 
     * @return void
     */
    public function method()
    {
        $this->parser->setMethod('method');
        $output = $this->parser->parse(false);
        
        $this->assertEqual($output[0]['method'], 'method', "I read @middleware->method('put', 'delete') annotation and i expect that the value is method.");
        $this->assertEqual($output[0]['params'][0], 'put', "i expect that the value is put.");
        $this->assertEqual($output[0]['params'][1], 'delete', "i expect that the value is delete.");

        TestOutput::varDump($output);
    }

    /**
     * Add
     *
     * @middleware->add('TrustedIp')
     * 
     * @return boolean(true)
     */
    public function add()
    {        
        $this->parser->setMethod('add');
        $output = $this->parser->parse(false);
        
        $this->assertEqual($output[0]['method'], 'add', "I read @middleware->add('TrustedIp') annotation and i expect that the value is add.");
        $this->assertEqual($output[0]['params'], 'TrustedIp', "i expect that the value is TrustedIp.");

        TestOutput::varDump($output);
    }

    /**
     * Remove
     *
     * @middleware->when('post', 'get')->add('TrustedIp')
     * 
     * @return void
     */
    public function when()
    {
        $this->parser->setMethod('when');
        $output = $this->parser->parse(false);
        
        $this->assertEqual($output[0]['method'], 'when', "I read @middleware->when('post', 'put', 'delete')->add('TrustedIp') annotation and i expect that the value is when.");
        $this->assertEqual($output[0]['params'][0], 'post', "i expect that the value is post.");
        $this->assertEqual($output[0]['params'][1], 'get', "i expect that the value is get.");
        $this->assertEqual($output[1]['method'], 'add', "i expect that the value is equal to add.");
        $this->assertEqual($output[1]['params'], 'TrustedIp', "i expect that the value is equal to TrustedIp.");

        TestOutput::varDump($output);
    }

    /**
     * Remove
     *
     * @middleware->add('TrustedIp')
     * @middleware->remove('TrustedIp')
     * 
     * @return void
     */
    public function remove()
    {
        $this->parser->setMethod('remove');
        $output = $this->parser->parse(false);
        
        $this->assertEqual($output[1]['method'], 'remove', "I read @middleware->remove('TrustedIp') annotation and i expect that the value is remove.");
        $this->assertEqual($output[1]['params'], 'TrustedIp', "i expect that the value is equal to TrustedIp.");

        TestOutput::varDump($output);
    }

}