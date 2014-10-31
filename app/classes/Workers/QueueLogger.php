<?php

namespace Workers;

use Obullo\Queue\Job,
    Obullo\Queue\JobInterface;

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
Class QueueLogger implements JobInterface
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
        $exp = explode('.', $job->getName());  // File, Mongo, Email ..
        $handlerName = ucfirst(end($exp));
        $JobHandlerClass = '\\Obullo\Log\Queue\JobHandler\JobHandler'.$handlerName;
        $JobHandlerName = strtolower($handlerName);

        switch ($JobHandlerName) {

        case 'file':
            $handler = new $JobHandlerClass($this->c, $this->c->load('config')['log']);
            break;

        case 'email':
            $handler = new $JobHandlerClass(
                $this->c,
                array(
                'mailer' => $this->c->load('service/mailer'),
                'from' => '<noreply@example.com> Server Admin',
                'to' => 'obulloframework@gmail.com',
                'cc' => '',
                'bcc' => '',
                'subject' => 'Server Logs',
                'message' => 'Detailed logs here --> <br /> %s',
                )
            );
            break;

        case 'mongo':
            $handler = new $JobHandlerClass($this->c,
               array(
                    'database' => 'db',
                    'collection' => 'logs',
                    'save_options' => null,
                    'format' => array(
                        'context' => 'array',  // json
                        'extra'   => 'array'   // json
                    ),
                )
            );
            break;

        default:
            $handler = null;
            break;
        }

        if ($handler != null) {
            
            $handler->write($data);  // Do job
            $handler->close();

            $job->delete();  // Delete job from queue
        }
    }

}

/* End of file QueueLogger.php */
/* Location: .app/classes/QueueLogger.php */