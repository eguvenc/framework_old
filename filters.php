<?php
/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
| This file specifies the your application filters.
*/
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
| Define your filters.
*/
$c['router']->filter(
    'auth', function () {
        echo '<pre>auth</pre>';
    }
);
/*
|--------------------------------------------------------------------------
| Csrf
|--------------------------------------------------------------------------
| Cross Site Request Forgery Filter
*/
$c['router']->filter(
    'csrf', function () use ($c) {
        $c->load('security/csrf')->init();  
        $c->load('security/csrf')->verify(); // Csrf protection check
    }
);
/*
|--------------------------------------------------------------------------
| Maintenance
|--------------------------------------------------------------------------
| Maintenance filter for application "all".
|
| Domain Key Example - Below the example subdomain we use "test" as domain key
|
|   test.examle.com
|
| Same key must be define in your config.xml file
|
|   <test>
|       <name>Test</name>
|       <domain><regex>test.example.com</regex></domain>
|       <maintenance>up</maintenance>
|   </test>
|
*/
$c['router']->filter(
    'maintenance', function ($domain) use ($c) {
        $c->load('app')->down('app.down', $domain);
    }
);


// @todo
// $c['router']->when('post', 'auth', array('post', 'put', 'delete')); // api için.  only authenticated users would be able to create, edit or delete posts from the application.
// $c['router']->when('post', 'auth', closure()));

/*
WHEN POST = GET DELETE $_GET = array();
if ($c['config']['uri']['queryStrings'] == false) {  // Is $_GET data allowed ? If not we'll set the $_GET to an empty array
    $_GET = array(); // @todo turn it to filter when('post' function() { }) 
}
*/
// $c['router']->attach('welcome/*', array('before' => 'auth'));
// $c['router']->attach('tutorials/hello_world/*', array('before' => 'auth'));


// $c['router']->group(
//     array('domain' => 'test.demo_blog', 'before' => 'auth'), 
//     function ($group) {
//         $this->attach('welcome/*', $group);
//         $this->attach('tutorials/hello_world/*', $group);
//     }
// );




/* End of file filters.php */
/* Location: .filters.php */