<?php

namespace Http\Filters;

/**
 * User auth activity filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
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
     * 
     * @return void
     */
    public function __construct($c)
    {
        $this->user = $c->load('return service/user');

        $this->user->activity->set('date', time());
        $this->user->activity->update();
    }
}

// END ActivityFilter class

/* End of file ActivityFilter.php */
/* Location: .Http/Filter/ActivityFilter.php */