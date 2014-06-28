<?php

namespace Service\Provider;

/**
 * Service Provider Interface
 * 
 * @category  Service
 * @package   Provider
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/container
 */
interface ProviderInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register($c);
}

// END ProviderInterface class

/* End of file ProviderInterface.php */
/* Location: .app/classes/Service/Provider/ProviderInterface.php */