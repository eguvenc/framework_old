<?php

namespace Views;

use Obullo\Http\Controller;

class Navbar extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $link = ($this->app->request->get('link')) ? $this->app->request->get('link') : 'welcome';

        $li = '';
        $navbar = [
            'welcome' => 'Welcome',
            'about'   => 'About', 
            'contact' => 'Contact',
        ];
        foreach ($navbar as $key => $value) {
            $class = '';
            if ($link == $key) {
                $class = ' class="active" ';
            }
            $li.= "<li $class>".$this->url->anchor('examples/layers/navbar?link='.$key, $value)."</li>";
        }
        
        echo $this->view->get(
            'navbar',
            [
                'li' => $li
            ]
        );
    }
}