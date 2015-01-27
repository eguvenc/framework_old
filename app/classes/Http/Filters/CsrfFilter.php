<?php

namespace Http\Filters;

use Obullo\Container\Container;

/**
 * Csrf security filter 
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class CsrfFilter
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Csrf
     * 
     * @var object
     */
    protected $csrf;

    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
        $this->csrf = $this->c->load('security/csrf'); 
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        $this->csrf->init();
        $this->csrf->verify(); // Csrf protection check
    }
}

// END CsrfFilter class

/* End of file CsrfFilter.php */
/* Location: .Http/Filters/CsrfFilter.php */