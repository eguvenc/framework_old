<?php

namespace Service;

use Obullo\Html\Html as OHtml;

/**
 * Html Service
 *
 * @category  Service
 * @package   Password
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
 */
Class Html implements ServiceInterface
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
        $c['html'] = function () use ($c) {
            return new OHtml(
                $c, 
                array(
                'cdn'     => false,
                'baseUrl' => $c['config']['url']['base'],
                'js'      => array('suffix' => 'assets/js/'),
                'css'     => array('suffix' => 'assets/css/')
                )
            );
        };
    }
}

// END Html class

/* End of file Html.php */
/* Location: .classes/Service/Html.php */