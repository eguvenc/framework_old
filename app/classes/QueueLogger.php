<?php

use Obullo\Queue\Job;

/**
 * Queue Logger
 *
 * @category  Queue
 * @package   QueueLogger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/queue
 */
Class QueueLogger
{
    /**
     * Container
     * 
     * @var object
     */
    public $c;

    /**
     * Environment
     * 
     * @var string
     */
    public $env;

    /**
     * Constructor
     * 
     * @param object $c   container
     * @param string $env environments
     */
    public function __construct($c, $env)
    {
        $this->c = $c;
        $this->env = $env;
    }

    /**
     * Fire the job
     * 
     * @param Job   $job  object
     * @param array $data data array
     * 
     * @return void
     */
    public function fire(Job $job, $data)
    {
        // echo $this->env;
        // echo $e;
        $exp = explode('.', LOGGER_QUEUE_NAME);
        $Handler = '\\Obullo\Log\Queue\JobHandler\\'.end($exp);

        $writer = new $Handler($this->c);
        $writer->write($data);

        print_r($data);
        $job->delete();
        // if ($data['type'] == 'cli') 
    }

}

/* End of file help.php */
/* Location: .app/classes/QueueLogger.php */