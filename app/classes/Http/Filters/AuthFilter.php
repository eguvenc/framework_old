<?php

namespace Http\Filters;

use Obullo\Container\Container;

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
Class AuthFilter
{
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
        $this->user = $c->load('user');
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
            
            $this->user->activity->set('date', time());
            $this->user->activity->update();        // Update user activity data
        }
    }
    
}

// END AuthFilter class

/* End of file AuthFilter.php */
/* Location: .Http/Filters/AuthFilter.php */