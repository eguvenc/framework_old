<?php

namespace Welcome;

// before filter run here 

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
        $this->c['url'];

        // load filter run here
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

        // after filter run here
    }

}


// after finish run here

/* End of file welcome.php */
/* Location: .controllers/welcome/welcome.php */