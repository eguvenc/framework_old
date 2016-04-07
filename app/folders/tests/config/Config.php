<?php

namespace Tests\Config;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;
use Obullo\Config\Config as ConfigClass;
use League\Container\Argument\RawArgument;

class Config extends TestController
{
    /**
     * Load file
     * 
     * @return void
     */
    public function get()
    {
        $maintenance = $this->config->get('maintenance');
        $this->assertArrayHasKey('root', $maintenance, "I load maintenance file and i expect that the array has 'root' key.");

        $config = $this->config->get('config');
        $this->assertArrayHasKey('locale', $config, "I load config file and i expect that the array has 'locale' key.");

        $session = $this->config->get('providers::session');
        if ($this->assertArrayHasKey('params', $session, "I load session provider file and i expect that the array has 'params' key.")) {
            $this->assertArrayHasKey('handler', $session['params'], "I expect that the session array has 'handler' key.");
        }
        $this->container->add('env', new RawArgument('test'));
        $test = new ConfigClass($this->container);

        $array = $test->get('config');

        if ($this->assertArrayHasKey('security', $array, "I set env as 'test' then i load config file and i expect that the array has 'security' key.")) {
            if ($this->assertArrayHasKey('encryption', $array['security'], "I expect that the security array has 'encryption' key.")) {
                $this->assertNotEqual($array['security']['encryption'], $config['security']['encryption'], "I expect that the security key value of test env is not equal to local env security key.");
            }
        }
    }

    public function write()
    {
        
    }

}