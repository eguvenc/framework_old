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
        $body = $this->template->make(
            'error',
            [
                'error' => 'Hello this my first custom error'
            ]
        );
        return $this->response
            ->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }

}