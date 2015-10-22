<?php

namespace Workers;

use Obullo\Queue\Job;
use Obullo\Queue\JobInterface;
use Obullo\Log\Filter\LogFilters;
use Obullo\Container\ContainerInterface;

use Obullo\Log\Handler\File;
use Obullo\Log\Handler\Mongo;
// use Obullo\Log\Handler\Email;

class Logger implements JobInterface
{
    /**
     * Application
     * 
     * @var object
     */
    protected $c;

    /**
     * Job class for queue operations
     * 
     * @var object
     */
    protected $job;

    /**
     * Common data for logger
     * 
     * @var array
     */
    protected $writers;

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
     * @param mixed $job  object|null
     * @param array $data log data
     * 
     * @return void
     */
    public function fire($job, array $data)
    {
        $this->job = $job;
        $this->writers = $data['writers'];
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
        foreach ($this->writers as $event) {

            switch ($event['handler']) {
            case 'file':
                $handler = new File(
                    $this->c['app'],
                    $this->c['config']
                );
                break;
            // case 'email':
            //     $mailer = $this->c['mailer'];
            //     $mailer->from('<noreply@example.com> Server Admin');
            //     $mailer->to('obulloframework@gmail.com');
            //     $mailer->subject('Server Logs');

            //     $handler = new Email(
            //         $this->c['app'],
            //         $this->c['config']
            //     );
            //     $handler->setMessage('Detailed logs here --> <div>%s</div>');
            //     $handler->setNewlineChar('<br />');
            //     $handler->func(
            //         function ($message) use ($mailer) {
            //             $mailer->message($message);
            //             $mailer->send();
            //         }
            //     );
            //     break;
            case 'mongo':
                $provider = $this->c['mongo']->get(
                    [
                        'connection' => 'default'
                    ]
                );
                $handler = new Mongo(
                    $provider,
                    $this->c['app'],
                    $this->c['config'],
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

            if (is_object($handler) && $handler->isAllowed($event)) { // Check write permissions
                
                $event = LogFilters::handle($event);

                $handler->write($event);  // Do job
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
    [primary] => 5
    [5] => Array
        (
            [request] => http
            [handler] => file
            [type] => writer
            [time] => 1436357633
            [filters] => Array
                        (
                            [0] => Array
                                (
                                    [class] => Obullo\Log\Filter\PriorityFilter
                                    [method] => notIn
                                    [params] => Array
                                        (
                                        )

                                )

                        )
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