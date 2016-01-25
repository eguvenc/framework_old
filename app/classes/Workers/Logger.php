<?php

namespace Workers;

use Obullo\Queue\Job;
use Obullo\Log\Filter;
use Obullo\Log\Handler\File;
use Obullo\Log\Handler\Mongo;
use Obullo\Queue\JobInterface;

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
    protected $writers;

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
        $request = $this->container->get('request');

        foreach ($this->writers as $event) {

            switch ($event['handler']) {
            case 'file':
                $handler = new File(
                    $this->container->get('logger.params'),
                    [
                        'path' => [
                            'http'  => '/resources/data/logs/http.log',
                            'cli'   => '/resources/data/logs/cli.log',
                            'ajax'  => '/resources/data/logs/ajax.log',
                        ],
                    ]
                );
                break;
            case 'mongo':
                $provider = $this->container->get('mongo')->shared(
                    [
                        'connection' => 'default'
                    ]
                );
                $handler = new Mongo(
                    $provider,
                    $this->container->get('logger.params'),
                    [
                        'database' => 'db',
                        'collection' => 'logs',
                        'save_options' => null,
                        'save_format' => [
                            'context' => 'array',  // json
                            'extra'   => 'array'   // json
                        ],
                    ]
                );
                break;
            default:
                $handler = null;
                break;
            }
            if (is_object($handler) && $handler->isAllowed($event, $request)) { // Check write permissions

                $filteredEvent = Filter::handle($event);

                $handler->write($filteredEvent);  // Do job
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
    [5] => Array
        (
            [handler] => file
            [request] => http
            [type] => writer
            [time] => 1445593189
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