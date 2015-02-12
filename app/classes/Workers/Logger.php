<?php

namespace Workers;

use Obullo\Queue\Job;
use Obullo\Log\PriorityQueue;
use Obullo\Queue\JobInterface;
use Obullo\Container\Container;
use Obullo\Log\Queue\QueueHandlerPriority;
use Obullo\Log\Handler\File as FileHandler;
use Obullo\Log\Handler\Mongo as MongoHandler;
use Obullo\Log\Handler\Email as EmailHandler;

/**
 * Log Worker
 *
 * @category  Workers
 * @package   Logger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/queue
 */
Class Logger implements JobInterface
{
    public $c;           // Container
    public $job;         // Job class
    public $queuedData;  // Log data

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
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
        $this->job = $job;
        
        if ($data['logger'] == 'QueueLogger') {  // Queue logger data

            $priority = new QueueHandlerPriority();
            $priority->insert($data);
            $this->queuedData = $priority->getQueue();
        }

        if ($data['logger'] == 'Logger') {      // Standart logger data
            $this->queuedData = $data;
        }
        $this->process();
    }

    /**
     * Process log data
     * 
     * @return void
     */
    protected function process()
    {
        foreach ($this->queuedData as $array) {

            switch ($array['handler']) {
            case 'file':
                $handler = new FileHandler($this->c);
                break;
            case 'email':
                $handler = new EmailHandler(
                    $this->c,
                    $this->c['mailer'],
                    array(
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
                $handler = new MongoHandler(
                    $this->c,
                    $this->c['service provider mongo']->get(['connection' => 'default']),
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
            if ($handler != null AND $handler->isAllowed($array['request'])) {

                $handler->write($array);  // Do job
                $handler->close();
                
                $this->job->delete();  // Delete job from queue
            }
        
        } // end foreach
    }
}

/* EXAMPLE LOG DATA
Array
(
    [logger] => QueueLogger
    [0] => Array
        (
            [request] => http
            [handler] => file
            [priority] => 5
            [time] => 1423677515
            [record] => Array
                (
                    [0] => Array
                        (
                            [channel] => system
                            [level] => debug
                            [message] => $_REQUEST_URI: /widgets/tutorials/hello_world
                            [context] => Array
                                (
                                )

                        )

                    [1] => Array
                        (
                            [channel] => system
                            [level] => debug
                            [message] => $_COOKIE: 
                            [context] => Array
                                (
                                )

                        )

        )

)

*/

/* End of file Logger.php */
/* Location: .app/classes/Workers/Logger.php */