<?php

namespace Service;

use Obullo\Container\Container,
    Obullo\Captcha\Adapter\Image,
    Obullo\ServiceProvider\ServiceInterface,

/**
 * Captcha Service
 *
 * @category  Service
 * @package   Captcha
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/container
 */
Class Captcha implements ServiceInterface
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
        $c['captcha'] = function () use ($c) {
            return new Image($c);
        };
    }
}

// END Captcha class

/* End of file Captcha.php */
/* Location: .classes/Service/Captcha.php */