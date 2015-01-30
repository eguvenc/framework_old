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

            $view = new ViewClass($c);
            /**
             * Creates your layouts using Layers
             */
            $view->setLayouts(
                [
                    'default' => function () {
                        $this->assign('header', $this->c['layer']->get('views/header'));
                        $this->assign('footer', $this->c['layer']->get('views/test'));
                    },
                ]
            );
            return $view;
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/View.php */