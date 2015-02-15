<?php

namespace Examples;

Class RestrictedArea extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['view'];
        $this->c['user'];
        $this->c['flash'];
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo '<h1>Restricted Area</h1>';

        echo $this->flash->output();

        echo '<section>';

        echo '<a href="/examples/logout">Logout</a>';
        echo '<pre>';
        print_r($this->user->identity->getArray());
        echo '</pre>';

        echo '</section>';
    }
}