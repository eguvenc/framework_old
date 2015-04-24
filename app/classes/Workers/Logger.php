<?php

namespace Workers;

use Obullo\Queue\Job;
use Obullo\Queue\JobInterface;
use Obullo\Container\Container;
use Obullo\Log\LogWriterRaidController;
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
class Logger implements JobInterface
{
    /**
     * Container
     * 
     * @var object
     */
    public $c;

    /**
     * Job class for queue operations
     * 
     * @var object
     */
    public $job;

    /**
     * Common data for logger
     * 
     * @var array
     */
    public $writers;

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
    public function fire($job, $data)
    {
        $this->job = $job;
        $this->writers = LogWriterRaidController::handle($data);  // Control multiple writers 
        $this->process();
    }

    /**
     * Process log data, standart logger use this method
     * thats why we declare it as public.
     * 
     * @return void
     */
    public function process()
    {
        foreach ($this->writers as $array) {

            switch ($array['handler']) {
            case 'file':
                $handler = new FileHandler($this->c);
                break;
            case 'email':
                $mailer = $this->c['app']->provider('mailer')->get(['driver' => 'mandrill']);

                $mailer->from('<noreply@example.com> Server Admin');
                $mailer->to('obulloframework@gmail.com');
                $mailer->subject('Server Logs');

                $handler = new EmailHandler($this->c);
                $handler->setMessage('Detailed logs here --> <div>%s</div>');
                $handler->setNewlineChar('<br />');
                $handler->func(
                    function ($message) use ($mailer) {
                        $mailer->message($message);
                        $mailer->send();

                        // $mailer->response->getArray();
                        // echo $mailer->printDebugger();  // debug : on / off
                    }
                );
                break;
            case 'mongo':
                $handler = new MongoHandler(
                    $this->c,
                    $this->c['app']->provider('mongo')->get(['connection' => 'default']),
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
            if ($handler != null AND $handler->isAllowed($array)) { // Check write permissions

                $handler->write($array);  // Do job
                $handler->close();
                
                if ($this->job instanceof Job) {
                    $this->job->delete();  // Delete job from queue
                }
            }
        
        } // end foreach
    }
}

/* EXAMPLE LOG DATA
Array
(
    [logger] => QueueLogger
    [primary] => 5
    [5] => Array
        (
            [request] => http
            [handler] => file
            [type] => writer
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
    [2] => Array
        (
            [request] => http
            [handler] => email
            [type] => handler
            [priority] => 5
            [time] => 1423677515
            [record] => Array
                (

)

*/

/* End of file Logger.php */
/* Location: .app/classes/Workers/Logger.php */