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
        $segment = ($this->app->uri->segment(0)) ? $this->app->uri->segment(0) : 'welcome';

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