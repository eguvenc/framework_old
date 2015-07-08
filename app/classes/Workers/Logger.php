<?php

namespace Workers;

use Obullo\Queue\Job;
use Obullo\Log\LogRaidManager;
use Obullo\Queue\JobInterface;
use Obullo\Container\ContainerInterface;

use Obullo\Log\Handler\File as FileHandler;
use Obullo\Log\Handler\Mongo as MongoHandler;
use Obullo\Log\Handler\Email as EmailHandler;

class Logger
{
    /**
     * Application
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
    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
    }

    /**
     * Fire the job
     * 
     * @param Job   $job  object
     * @param array $data log data
     * 
     * @return void
     */
    public function fire($job, $data)
    {
        $this->job = $job;
        $this->writers = LogRaidManager::handle($data);  // Control multiple writers 
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
        foreach ($this->writers as $data) {

            switch ($data['handler']) {
            case 'file':
                $handler = new FileHandler;
                break;
            case 'email':
                $mailer = $this->c['app']->provider('mailer')->get(['driver' => 'mandrill']);

                $mailer->from('<noreply@example.com> Server Admin');
                $mailer->to('obulloframework@gmail.com');
                $mailer->subject('Server Logs');

                $handler = new EmailHandler;
                $handler->setMessage('Detailed logs here --> <div>%s</div>');
                $handler->setNewlineChar('<br />');
                $handler->func(
                    function ($message) use ($mailer) {
                        $mailer->message($message);
                        $mailer->send();

                        // echo $mailer->printDebugger();
                    }
                );
                break;
            case 'mongo':
                $provider = $this->c['app']->provider('mongo')->get(['connection' => 'default']);

                $handler = new MongoHandler(
                    $provider,
                    array(
                        'database' => 'db',
                        'collection' => 'logs',
                        'save_options' => null,
                        'save_format' => array(
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
            if (is_object($handler) && $handler->isAllowed($data)) { // Check write permissions

                $handler->write($data);  // Do job
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
    [logger] => Logger
    [primary] => 5
    [5] => Array
        (
            [request] => http
            [handler] => file
            [type] => writer
            [time] => 1436357633
            [record] => Array
                (
                    [0] => Array
                        (
                            [channel] => system
                            [level] => debug
                            [message] => Uri Class Initialized
                            [context] => Array
                                (
                                    [uri] => /welcome/index
                                )

                        )
    [6] => Array(
        // second writer data
    )
*/