<?php

namespace Workers;

use Obullo\Queue\Job,
    Obullo\Queue\JobInterface,
    Obullo\Log\PriorityQueue,
    Obullo\Log\JobHandlerPriority,
    Obullo\Log\JobHandler\JobHandlerFile,
    Obullo\Log\JobHandler\JobHandlerMongo,
    Obullo\Log\JobHandler\JobHandlerEmail;

/**
 * Log Worker
 *
 * @category  Workers
 * @package   Logger
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/queue
 */
Class Logger implements JobInterface
{
    /**
     * Container
     * 
     * @var object
     */
    public $c;

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct($c)
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
        $priority = new JobHandlerPriority();
        $priority->insert($data);

        foreach ($priority->getQueue() as $array) {

            switch ($array['handler']) {
            case 'file':
                $handler = new JobHandlerFile($this->c);
                break;
            case 'email':
                $handler = new JobHandlerEmail(
                    $this->c,
                    $this->c->load('service/mailer'),
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
                $handler = new JobHandlerMongo(
                    $this->c,
                    $this->c->load('service/provider/mongo', 'db'),
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

                $job->delete();  // Delete job from queue
            }
        
        } // end foreach

    } // end method

}

/* End of file Logger.php */
/* Location: .app/classes/Workers/Logger.php */