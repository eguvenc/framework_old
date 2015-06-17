<?php

namespace Welcome;

class Welcome extends \Controller
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

        // $this->c['response']->callback(
        //     function ($response) {

        //         // $response->headers->set('content-type', 'text/plain');

        //         list($status, $headers, $options, $output) = $response->finalize();
        //         $response->sendHeaders($status, $headers, $options);

        //         echo $output; // Send output
        //     }
        // );

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
/* Location: .modules/welcome/welcome.php */