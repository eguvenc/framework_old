<?php

namespace Tests\Cache;

use Obullo\Tests\TestOutput;
use Obullo\Tests\TestController;

class Config extends TestController
{
    public function load()
    {
        $maintenance = $this->config->load('maintenance');

        var_dump($maintenance);
    }

}