<?php

namespace Service;

use Service\ServiceInterface,
    Obullo\Permissions\RbacService;

/**
 * Rbac PermissionService ( Shared )
 *
 * @category  Service
 * @package   Mail
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/services
 */
Class Rbac implements ServiceInterface
{
    /**
     * Configure servide parameters
     * 
     * @param object $c container
     */
    public function __construct($c)
    {
        $c['config']['rbac.params.database'] = array('db' => 'db', 'provider' => 'mysql');  // set provider parameters
    }

    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register($c)
    {
        $c['rbac'] = function () use ($c) {
            return new RbacService($c);
        };
    }
}

// END Rbac class

/* End of file Rbac.php */
/* Location: .classes/Service/Rbac.php */