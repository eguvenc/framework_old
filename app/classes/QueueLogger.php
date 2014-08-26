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
        echo $e;
        $exp = explode('.', $job->getName());  // File, Mongo, Email ..
        $handlerName = ucfirst(end($exp));
        $JobHandlerClass = '\\Obullo\Log\Queue\JobHandler\JobHandler'.$handlerName;
        $JobHandlerName = strtolower($handlerName);

        switch ($JobHandlerName) {
        case LOGGER_FILE:
            $writer = new $JobHandlerClass($this->c);
            break;
        case LOGGER_EMAIL:
            $writer = new $JobHandlerClass(
                $this->c,
                array(
                'from' => '<noreply@example.com> Server Admin',
                'to' => 'eguvenc@gmail.com',
                'cc' => '',
                'bcc' => '',
                'subject' => 'Server Logs',
                'message' => 'Detailed logs here --> <br /> %s',
                )
            );
            break;  
        case LOGGER_MONGO:
            $writer = new $JobHandlerClass($this->c,
                array(
                'database' => 'db',
                'collection' => 'logs',
                'save_options' => null
                )
            );
            break;
        default:
            $writer = null;
            break;
        }
        if ($writer != null) {
            $writer->write($data);  // Do job

            // throw new Exception("test");

            $writer->close();
            $job->delete();  // Delete job from queue
        }
    }

}

/* End of file QueueLogger.php */
/* Location: .app/classes/QueueLogger.php */