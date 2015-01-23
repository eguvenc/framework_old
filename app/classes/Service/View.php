<?php

namespace Service;

use Obullo\View\View as ViewClass;

/**
 * View Service
 *
 * @category  Service
 * @package   Cache
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/providers
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
    public function register($c)
    {
        $c['view'] = function () use ($c) {

            $view = new ViewClass($c);

            /**
             * Creates your layouts using Layers
             */
            $view->setLayouts(
                array(
                    'default' => function () {
                        $this->assign('header', $this->c['layer']->get('views/header'), false);
                        $this->assign('sidebar', $this->c['layer']->get('views/sidebar'), false);
                        $this->assign('footer', $this->c['layer']->get('views/footer'), false);
                    },
                    'welcome' => function () {
                        $this->assign('footer', $this->template('footer'), false);
                    },
                )
            );
            return $view;
        };
    }
}

// END Cache class

/* End of file Cache.php */
/* Location: .classes/Service/View.php */