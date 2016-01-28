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
		// echo $this->url->withHost()
		// 	->withScheme('http')
		// 	->withPath('examples/forms');

		// echo "<br>";

		// echo $this->url->getBaseUrl('examples/forms');

		// echo "<br>";

		// echo $this->url->getCurrentUrl();

		// echo "<br>";
		
		// echo $this->url->withHost()->withScheme('http');

		// echo "<br>";

		// echo $this->url->withHost('example.com')
		// 	->withScheme('http')
		// 	->withPath('en')
  //   		->withQuery("a=1&b=2")
		// 	->getUriString();  // https://example.com/en

		// echo "<br>";

		// echo $this->url->withHost()
		// 	->withScheme('https')
		// 	->withPath('de')
		// 	->withAnchor();  // <a href="https://example.com/en">https://example.com/en</a>

		// echo "<br>";

		// echo $this->url->withHost('example.com')
		// 	->withScheme('https')
		// 	->withPath('en')
		// 	->withAnchor('Test');  // <a href="https://example.com/en">Test</a>

		// echo "<br>";

		// echo $this->url->withHost('example.com')
		// 	->withScheme('https')
		// 	->withAsset('images/logo.png');  // https://example.com/assets/images/logo.png

		// echo '<br>';

		// echo $this->url->anchor('welcome', 'Welcome'); //  //myproject/welcome

		// echo '<br>';

		// echo $this->url->asset('images/logo.png'); // /assets/images/logo.png 

        $this->view->load('welcome');
    }
}