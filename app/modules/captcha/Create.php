<?php

namespace Captcha;

use Obullo\Http\Controller;

class Create extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->response->newInstance(
            'php://memory',
            200,
            [
                'Cache-Control' => 'no-cache, must-revalidate',
                'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                'Content-Type' => 'image/png',
            ]
        );
        $this->captcha->create();
    }
}