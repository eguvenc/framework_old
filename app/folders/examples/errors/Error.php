<?php

namespace Examples\Errors;

use Obullo\Http\Controller;

class Error extends Controller
{
    /**
     * Index
     * 
     * @return response
     */      
    public function index()
    {
        $body = $this->view->withStream()->get(
            'templates::error',
            [
                'error' => 'This is my first custom error !'
            ]
        );
        return $this->response
            ->withStatus(501)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }

}