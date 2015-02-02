<?php

namespace Service;

use Obullo\Container\Container,
    Obullo\View\View as ViewClass,
    Obullo\ServiceProvider\ServiceInterface;

/**
 * View Service
 *
 * @category  Service
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class View implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(Container $c)
    {
        $c['view'] = function () use ($c) {
            return new ViewClass($c);
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/View.php */