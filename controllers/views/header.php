<?php

namespace Views;

Class Header extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('view');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $firstSegment   = $this->request->global->uri->segment(0);     // Get first segnment
        $currentSegment = (empty($firstSegment)) ? 'home' : $firstSegment;  // Set current segment as "home" if its empty

        $li = '';
        $navbar = array(
            'home'    => 'Home',
            'about'   => 'About', 
            'contact' => 'Contact',
            'membership/login'   => 'Login',
            'membership/signup'  => 'Signup',
        );
        foreach ($navbar as $key => $value) {
            $active = ($currentSegment == $key) ? ' id="active" ' : '';
            $li.= '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
        }

        echo $this->view->load(
            'header',
            function () use ($li) {
                $this->assign('li', $li);
            },
            false
        );
    }
}


/* End of file header.php */
/* Location: .controllers/views/header.php */