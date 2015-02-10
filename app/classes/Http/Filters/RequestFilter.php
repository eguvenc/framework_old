<?php

namespace Http\Filters;

use Obullo\Container\Container;
use Obullo\Application\Addons\BenchmarkTrait;
use Obullo\Application\Addons\SanitizeSuperGlobalsTrait;

class RequestFilter
{
    use BenchmarkTrait;
    use SanitizeSuperGlobalsTrait;   // You can add / remove addons.

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
        $this->sanitize();
        $this->benchmarkStart();
    }

    /**
     * After the response
     * 
     * @return void
     */
    public function finish()
    {
        $this->benchmarkEnd();
    }
    
}

// END RequestFilter class

/* End of file RequestFilter.php */
/* Location: .Http/Filters/RequestFilter.php */