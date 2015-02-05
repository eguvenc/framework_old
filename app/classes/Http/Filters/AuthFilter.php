<?php

namespace Http\Filters;

use Obullo\Container\Container,
    Obullo\Authentication\Addons\UniqueLoginTrait;

/**
 * User auth authority filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
class AuthFilter
{
    use UniqueLoginTrait;  // You can add / remove addons.

    /**
     * User service
     * 
     * @var object
     */
    protected $user;

    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct(Container $c)
    {
        $this->user = $c['user'];
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        if ($this->user->identity->check()) {

            // Do something

            $this->uniqueLoginCheck();
            $this->user->activity->set('date', time());  //  example activity data
        }
    }
    
    /**
     * After the response
     * 
     * @return void
     */
    public function finish()
    {
        $this->user->activity->write();  // Write user activity data
    }

}

// END AuthFilter class

/* End of file AuthFilter.php */
/* Location: .Http/Filters/AuthFilter.php */