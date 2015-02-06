<?php

namespace Http\Filters;

use Obullo\Container\Container;
use Obullo\Authentication\Addons\UniqueLoginTrait;

class AuthFilter
{
    use UniqueLoginTrait;  // You can add / remove addons.

    /**
     * Container
     * 
     * @var object
     */
    protected $c;

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
        $this->c = $c;
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

            $this->uniqueLoginCheck();

            // Do something 

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