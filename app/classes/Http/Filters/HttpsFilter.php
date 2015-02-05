<?php

namespace Http\Filters;

use Obullo\Container\Container,
    Obullo\Application\Addons\RewriteHttpsTrait;

/**
 * Https filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class HttpsFilter
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