<?php

namespace Http\Filters;

use Obullo\Container\Container;
use Obullo\Application\Addons\RewriteHttpsTrait;

class HttpsFilter
{
    use RewriteHttpsTrait;

    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        $this->rewrite();
    }
}

// END HttpsFilter class

/* End of file HttpsFilter.php */
/* Location: .Http/Filters/HttpsFilter.php */