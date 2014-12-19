<?php

namespace Service;

/**
 * Service Interface
 * 
 * @category  Service
 * @package   Service
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT
 * @link      http://obullo.com/package/container
 */  
interface ServiceInterface
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

// END ServiceInterface class

/* End of file ServiceInterface.php */
/* Location: .app/classes/Service/ServiceInterface.php */