<?php

namespace Http\Filters;

/**
 * User auth activity filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class ActivityFilter
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
    public function __construct($c)
    {
        $this->user = $c->load('return service/user');
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        $this->user->activity->set('date', time());
        $this->user->activity->update();

        echo 'activity !!';
    }
}

// END ActivityFilter class

/* End of file ActivityFilter.php */
/* Location: .Http/Filters/ActivityFilter.php */