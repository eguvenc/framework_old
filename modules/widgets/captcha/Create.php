<?php

namespace Widgets\Captcha;

Class Create extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['captcha'];
    }

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

/* End of file create.php */
/* Location: .controllers/widgets/captcha/create.php */