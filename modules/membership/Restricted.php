<?php

namespace Membership;

class Restricted extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo '<html>';
        echo '<head>';
        echo '</head><body>';

        echo '<h1>Restricted Area</h1>';

        echo $this->flash->output();

        echo '<section>';

        echo '<a href="/membership/logout">Logout</a>';
        echo '<pre>';
        print_r($this->user->identity->getArray());
        echo '</pre>';

        echo '</section>';
        echo '</body>';
        echo '</html>';
    }
}