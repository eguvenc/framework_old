<?php

namespace Examples\Logger;

use Obullo\Http\Controller;

class Logger extends Controller
{
    /**
     * Index
     * 
     * @return void
     */      
    public function index()
    {
        $this->logger->debug('Example debug level log.', array('foo' => 'bar'));
        $this->logger->error('Example error level log.', array('foo' => 'bar'));
        $this->logger->alert('Example alert level log.', array('foo' => 'bar'));
        $this->logger->warning('Example warning level log.', array('foo' => 'bar'));
        $this->logger->info('Example info level log.', array('foo' => 'bar'));
        $this->logger->emergency('Example emergency level log.', array('foo' => 'bar'));
        $this->logger->critical('Example critical level log.', array('foo' => 'bar'));
        $this->logger->notice('Example notice level log.', array('foo' => 'bar'));

        $this->view->load('logger');
    }
}