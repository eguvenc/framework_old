<?php

namespace Examples\Errors;

use Obullo\Http\Controller;

class NotFound extends Controller
{
    /**
     * Index
     * 
     * @return response
     */      
    public function index()
    {
        $body = $this->view->getStream('templates::404');
        
        return $this->response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }

}