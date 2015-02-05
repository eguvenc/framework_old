<?php

namespace Http\Filters;

use Obullo\Container\Container,
    Obullo\Application\Addons\BenchmarkTrait,
    Obullo\Application\Addons\SanitizeSuperGlobalsTrait;

/**
 * Global http request filter
 *
 * @category  Request
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/filters
 */
Class RequestFilter
{
    use BenchmarkTrait,
        SanitizeSuperGlobalsTrait;   // You can add / remove addons.

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