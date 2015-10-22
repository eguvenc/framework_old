<?php

namespace Widgets\Tutorials;

class Hello404 extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */      
    public function index()
    {
        $body = $this->template->make('404');
        
        return $this->response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }

}