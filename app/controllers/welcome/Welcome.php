<?php

namespace Welcome;

Class Welcome extends \Controller
{
    use \View\Layout\Base;

    /**
     * Loader
     *
     * @filter->method("get");
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
    }

    /**
     * Index
     *
     * @filter->method("get");
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !',
            ]
        );
    }

}


// after finish run here

/* End of file welcome.php */
/* Location: .controllers/welcome/welcome.php */