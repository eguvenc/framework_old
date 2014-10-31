<?php

namespace Log\Logger\Handlers;

use Obullo\Log\Handler\EmailHandler;

/**
 * Email Handler
 * 
 * @category  Log
 * @package   Handler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class Email
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Handler closure
     * 
     * @var object
     */
    protected $closure;

    /**
     * Handler priority
     * 
     * @var integer
     */
    protected $priority;

    /**
     * Constructor
     * 
     * @param object  $c        container
     * @param integer $priority priority
     */
    public function __construct($c, $priority = 1)
    {
        $this->closure = function () use ($c) {

            return new EmailHandler(
                $c,
                $c->load('service/mailer')
            );
        };
        $this->priority = $priority;
    }

    /**
     * Returns to closure data of handler
     * 
     * @return object closure
     */
    public function getHandler()
    {
        return $this->closure;
    }

    /**
     * Handler priority
     * 
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

}

// END Email class

/* End of file Email.php */
/* Location: .app/Log/Logger/Handlers/Email.php */