<?php

namespace Service;

use Obullo\Captcha\Captcha as OCaptcha;

/**
 * Captcha Service
 *
 * @category  Security
 * @package   Captcha
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/services
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
    public function register($c)
    {
        $c['captcha'] = function () use ($c) {
            return new OCaptcha($c, $c->load('config')->load('captcha'));
        };
    }
}

// END Captcha class

/* End of file Cache.php */
/* Location: .classes/Service/Captcha.php */