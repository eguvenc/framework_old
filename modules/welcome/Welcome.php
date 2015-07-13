<?php

namespace Welcome;

class Welcome extends \Controller
{
    use \View\Layout\Base;

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {        
        $this->logger->alert("alert test");
        $this->logger->info("info test");
        
        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !',
            ]
        );
    }
}


/* End of file welcome.php */
/* Location: .modules/welcome/welcome.php */