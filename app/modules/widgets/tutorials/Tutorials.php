<?php

namespace Widgets\Tutorials;

use Obullo\Http\Controller;

class Tutorials extends Controller
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

    public function test()
    {
        echo 'asds';
    }

}