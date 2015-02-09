<?php

namespace Views;

Class Test extends \Controller
{
    /**
     * Loader
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
     * @return void
     */
    public function index()
    {
        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->c['request']->global->uri->getUriString().'</span></pre>';
        echo '<p>-----------------------------------------</p>';
    }
}

/* End of file test.php */
/* Location: .controllers/views/test.php */