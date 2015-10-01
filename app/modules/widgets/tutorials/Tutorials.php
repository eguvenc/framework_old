<?php

namespace Widgets\Tutorials;

class Tutorials extends \Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
    	echo 'widgets/tutorials/tutorials.php<br>';
        echo get_class();
    }

}