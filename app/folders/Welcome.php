<?php

use Obullo\Http\Controller;

class Welcome extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {

		$all = array
		(
		    0 => 307,
		    1 => 157,
		    2 => 234,
		    3 => 200,
		    4 => 322,
		    5 => 324
		);
		$search_this = array
		(
		    0 => 200,
		    1 => 234
		);

		$containsSearch = count(array_intersect($search_this, $all)) == count($search_this);

		var_dump($containsSearch);

        $this->view->load('views::welcome');
    }
}