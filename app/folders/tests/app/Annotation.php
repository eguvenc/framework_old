<?php

namespace Tests\App;

use Obullo\Http\TestController;

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
        $reflector = new \ReflectionClass($this);
        $controller = new \Obullo\Application\Annotations\Controller;
        $controller->setContainer($container);
        $controller->setReflectionClass($reflector);
        $this->parser = $controller;
    }

    /**
     * Index (Disable annotations from config.php file !)
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'tests::index',
            ['content' => $this->getClassMethods()]
        );
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
        
        $this->assertEqual($output[0]['method'], 'method', "I read @middleware->method('put', 'delete') annotation and i expect value is method.");
        $this->assertEqual($output[0]['params'][0], 'put', "I expect value is put.");
        $this->assertEqual($output[0]['params'][1], 'delete', "I expect value is delete.");

        $this->varDump($output);
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
        
        $this->assertEqual($output[0]['method'], 'add', "I read @middleware->add('TrustedIp') annotation and i expect value is add.");
        $this->assertEqual($output[0]['params'], 'TrustedIp', "I expect value is TrustedIp.");

        $this->varDump($output);
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
        
        $this->assertEqual($output[0]['method'], 'when', "I read @middleware->when('post', 'put', 'delete')->add('TrustedIp') annotation and i expect value is when.");
        $this->assertEqual($output[0]['params'][0], 'post', "I expect value is post.");
        $this->assertEqual($output[0]['params'][1], 'get', "I expect value is get.");
        $this->assertEqual($output[1]['method'], 'add', "I expect value is equal to add.");
        $this->assertEqual($output[1]['params'], 'TrustedIp', "I expect value is equal to TrustedIp.");

        $this->varDump($output);
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
        
        $this->assertEqual($output[1]['method'], 'remove', "I read @middleware->remove('TrustedIp') annotation and i expect value is remove.");
        $this->assertEqual($output[1]['params'], 'TrustedIp', "I expect value is equal to TrustedIp.");

        $this->varDump($output);
    }

}