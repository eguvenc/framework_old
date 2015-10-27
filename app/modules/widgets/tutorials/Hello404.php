<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class Hello404 extends Controller
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