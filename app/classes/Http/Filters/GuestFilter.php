<?php

namespace Http\Filters;

use Obullo\Container\Container;

class GuestFilter
{
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
        $this->user = $this->c['user'];
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        if ($this->user->identity->guest()) {

            $this->c['flash']->info('Your session has been expired.');
            $this->c['url']->redirect('/welcome');
        }
    }

}

// END GuestFilter class

/* End of file GuestFilter.php */
/* Location: .Http/Filters/GuestFilter.php */