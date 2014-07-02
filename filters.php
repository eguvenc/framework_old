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


/* End of file filters.php */
/* Location: .filters.php */