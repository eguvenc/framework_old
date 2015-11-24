<?php

namespace Examples\Errors;

use Obullo\Http\Controller;

class HelloError extends Controller
{
    /**
     * Index
     * 
     * @return void
     */      
    public function index()
    {
        $body = $this->template->make(
            'error',
            [
                'error' => 'Hello this my custom error string'
            ]
        );
        
        return $this->response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }

}