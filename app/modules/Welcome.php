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
		// echo $this->url->createUrl('example.com')
		// 	->withScheme('https')
		// 	->withPath('en')
		// 	->getUriString();  // https://example.com/en

		// echo "<br>".$this->url->getPath();  // en

		// echo $this->url->createUrl('example.com')
		// 	->withScheme('https')
		// 	->withPath('de')
		// 	->makeAnchor();  // <a href="https://example.com/en">https://example.com/en</a>

		// echo "<br>".$this->url->getPath(); // de

		// echo $this->url->createUrl('example.com')
		// 	->withScheme('https')
		// 	->withPath('en')
		// 	->makeAnchor('Test');  // <a href="https://example.com/en">Test</a>

		// echo "<br>".$this->url->getScheme(); // https

		// echo '<br>';

		// echo $this->url->createUrl('example.com')
		// 	->withScheme('https')
		// 	->makeAsset('images/logo.png');  // https://example.com/assets/images/logo.png

		// echo '<br>';

		// echo $this->url->anchor('welcome', 'Welcome'); //  //myproject/welcome

		// echo '<br>';

		// echo $this->url->asset('images/logo.png'); // /assets/images/logo.png 

        $this->view->load('welcome');
    }
}