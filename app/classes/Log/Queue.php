<?php

namespace Log;

use Obullo\Queue\JobInterface;

use League\Container\ImmutableContainerAwareTrait;
use League\Container\ImmutableContainerAwareInterface;

class Queue implements JobInterface, ImmutableContainerAwareInterface
{
    use ImmutableContainerAwareTrait;

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
        $queue = $this->getContainer()->get('queue');
        $params = $this->getContainer()->get('logger.params');

        $queue->push(
            'Workers@Logger',
            $job->getName(),
            $data,
            $params['queue']['delay']
        );
    }

}
