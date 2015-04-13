<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Application\Addons\RewriteLocaleTrait;

class RewriteLocale extends Middleware
{
    use RewriteLocaleTrait;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->next->load();
    }

    /**
     *  Call action
     * 
     * @return void
     */
    public function call()
    {
        $this->rewrite();

        $this->next->call();
    }


}