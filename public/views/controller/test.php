<?php

Class Test extends Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->request->globals('uri')->getUriString().'</span></pre>';
        echo '<p>-----------------------------------------</p>';
    }
}


/* End of file test.php */
/* Location: .public/views/controller/test.php */