<?php

namespace Http\Middlewares;

use Obullo\Application\Middleware;
use Obullo\Application\Addons\RewriteLocaleTrait;
use Obullo\Application\Addons\SetDefaultLocaleTrait;

class Translation extends Middleware
{
    use RewriteLocaleTrait;
    use SetDefaultLocaleTrait;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->setLocale();   // We need to set locale at the top
        // $this->rewrite();

        $this->next->load();
    }

    /**
     *  Call action
     * 
     * @return void
     */
    public function call()
    {
        $this->next->call();
    }
}