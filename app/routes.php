<?php
/*
|--------------------------------------------------------------------------
| Routes 
|--------------------------------------------------------------------------
| Typically there is a one-to-one relationship between a URL string and its 
| corresponding ( directory / controller ).
|
*/
//$c['router']->route('*', '([0-9]+)/([a-z]+)', 'welcome/$1/$2');

$c['router']->domain($c['config']['url']['webhost']);  // Root domain
$c['router']->defaultPage('welcome');


// $c['router']->error404('errors/custom404');

// $c['router']->get(
//     'welcome/([0-9]+)/([a-z]+)', 'welcome/$1/$2',
//     function () use ($c) {
//         $c['view']->load('dummy');
//     }
// )->attach('welcome/(.*)',  array('activity')); 

// $c['router']->attach('(.)', array('maintenance'));

$c['router']->group(
    [
        'name' => 'GenericUsers', 
        'domain' => $c['config']['domain']['mydomain.com'],
        'middleware' => array()
    ],
    function () use ($c) {

            $this->defaultPage('welcome');

            // $this->match(['get', 'post'], 'widgets/tutorials/helloForm')->middleware('Csrf');

            $this->get('(?:en|tr|de|nl)/(.*)', '$1');
            $this->get('(?:en|tr|de|nl)', 'welcome');  // default controller

            $this->attach('.*'); // all urls
    }
);


$c['router']->group(
    ['name' => 'AuthorizedUsers', 'middleware' => array('Auth', 'Guest')],  //  Auth // Guest
    function () {

        $this->defaultPage('welcome');
        $this->attach('membership/restricted');

        // $this->get('tutorials/helloWorld.*', 'tutorials/helloLayout');
        // $this->attach('(.*)'); // all url
        // $this->attach('((?!tutorials/helloWorld).)*$');  // url not contains "tutorials/hello_world"
    }
);



// $c['router']->error404('errors/page_not_found');

// Example Api


// $c['router']->match(array('get', 'post'), 'welcome', 'welcome/test');

// 'routes' => array(

//     '(tr|en)/(:any)'               => '$2',
//     'tag/(:any)'                   => 'tag/$1',
//     'post/detail/(:num)'           => 'post/detail/$1',
//     'post/preview/(:num)'          => 'post/preview/$1',
//     'post/update/(:num)'           => 'post/update/$1',
//     'post/delete/(:num)'           => 'post/delete/$1',
//     'comment/delete/(:num)'        => 'comment/delete/$1',
//     'comment/update/(:num)/(:any)' => 'comment/update/$1/$2',

//     'default_controller' => 'welcome/index', // This is the default controller, application call it as default
//     '404_override' => '',                    // You can redirect 404 errors to specify controller

//      // Controller Default Method
//     'index_method' => 'index'                // This is controller default index method for all controllers.
//                                              // You should configure it before the first run of your application.
// ),