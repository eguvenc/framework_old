<?php

namespace Workers;

use Obullo\Queue\Job;
use Obullo\Log\Filter;
use Obullo\Log\Handler\File;
use Obullo\Log\Handler\Mongo;
use Obullo\Queue\JobInterface;
use Obullo\Log\Handler\MetadataAwareInterface;

use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

class Logger implements JobInterface, ImmutableContainerAwareInterface
{
    use ImmutableContainerAwareTrait;

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
    protected $data;

    /**
     * Fire the job
     * 
     * @param array $data log data
     * @param mixed $job  object|null
     * 
     * @return void
     */
    public function fire(array $data, $job = null)
    {
        $this->job = $job;
        $this->data = $data;

        if ($data['meta']['request'] == 'worker') {   // Disable worker server logs.
            return;
        }
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
        foreach ($this->data['writers'] as $event) {

            switch ($event['handler']) {
            case 'file':
                $handler = new File(
                    $this->container->get('logger.params'),
                    [
                        'path' => [
                            'http'  => '/resources/data/logs/http.log',
                            'cli'   => '/resources/data/logs/cli.log',
                            'ajax'  => '/resources/data/logs/ajax.log',
                            'worker' => '/resources/data/logs/worker.log',
                        ],
                    ]
                );
                break;
            case 'mongo':
                $handler = new Mongo(
                    $this->container->get('logger.params'),
                    $this->container->get('mongo')->shared(['connection' => 'default']),
                    [
                        'database' => 'db',
                        'collection' => 'logs',
                        'options' => array(),
                        'encoding' => [
                            'context' => 'array',  // json
                            'extra'   => 'array',  // json
                        ]
                    ]
                );
                break;
            default:
                $handler = null;
                break;
            }

            if (is_object($handler)) {

                $filteredEvent = Filter::handle($event);

                if ($handler instanceof MetadataAwareInterface) {
                    $handler->setMetadata($this->data['meta']);
                }
                $handler->write($filteredEvent);  // Do job
                $handler->close();

                if ($this->job instanceof Job) {
                    $this->job->delete();  // Delete job from queue
                }
            }
        
        } // end foreach
    }

}

/* Event Data

Array
(
    meta =>  Array(

        [request] => http
        [time] => 1445593189
        [host] => example.com
        [uri] => /welcome/index
    ),

    writers => Array(

        [5] => Array
            (
                [handler] => file
                [type] => writer
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
                [records] => Array
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
        
            // handler data
        )
    )
*/