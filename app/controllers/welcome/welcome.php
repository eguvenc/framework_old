<?php

namespace Welcome;

Class Welcome extends \Controller
{
    use \View\Layout\Base;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');

        $this->c['model.user'] = new \Model\User;
        $this->c['model.user']->test();
    }

    /**
     * Index
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

/* End of file welcome.php */
/* Location: .controllers/welcome/welcome.php */