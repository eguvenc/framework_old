<?php

namespace Captcha;

class Create extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

        $this->captcha->create();
    }
}