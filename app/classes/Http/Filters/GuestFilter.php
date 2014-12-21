<?php

namespace Http\Filters;

/**
 * Guest auth authority filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class GuestFilter
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
    public function __construct($c)
    {
        $this->c = $c;
        $this->user = $c->load('return service/user');
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        if ($this->user->identity->isGuest()) {

            $this->c->load('flash/session')->info('Your session has been expired.');
            $this->c->load('url')->redirect('/');
        }
    }

}

// END GuestFilter class

/* End of file GuestFilter.php */
/* Location: .Http/Filters/GuestFilter.php */