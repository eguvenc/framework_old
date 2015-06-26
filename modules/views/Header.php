<?php

namespace Views;

class Header extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $firstSegment = $this->app->uri->segment(0);
        $segment = (empty($firstSegment)) ? 'welcome' : $firstSegment;

        $li = '';
        $navbar = array(
            'welcome'    => 'Welcome',
            'about'   => 'About', 
            'contact' => 'Contact',
            'membership/login'  => 'Login',
            'membership/signup' => 'Signup',
        );
        foreach ($navbar as $key => $value) {
            $active = ($segment == $key) ? ' id="active" ' : '';
            $li.= '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
        }

        echo $this->view->get(
            'header',
            [
                'li' => $li
            ]
        );
    }
}