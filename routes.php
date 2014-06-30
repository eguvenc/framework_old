<?php
/*
|--------------------------------------------------------------------------
| Routes 
|--------------------------------------------------------------------------
| Typically there is a one-to-one relationship between a URL string and its 
| corresponding ( directory/controller ).
|
*/
//$c['router']->route('*', '([0-9]+)/([a-z]+)', 'welcome/$1/$2');

$c['router']->domain($c->load('config')['url']['host']);  // Root domain
$c['router']->route('*', 'jelly/(.+)', 'widgets/jelly/$1');  // Rewrite "jform" uri to widgets/ folders

// $c['router']->route(
//     '*', 'welcome/([0-9]+)/([a-z]+)', 'welcome/$1/$2', 
//     function () use ($c) {
//         $c->load('view')->load('dummy');
//     }
// );

$c['router']->route('get', '(en|tr)/(.+)', '$2');
$c['router']->route('get', '(en|tr)', 'welcome/index');
$c['router']->route('get', 'tag/(.+)', 'tag/$1');
$c['router']->route('get', 'post/detail/([0-9])', 'post/detail/$1');
$c['router']->route('get', 'post/preview/([0-9])', 'post/preview/$1');
$c['router']->route('get', 'post/update/([0-9])', 'post/update/$1');
$c['router']->route('post', 'comment/delete/([0-9])', 'comment/delete/$1');
$c['router']->route('post', 'comment/update/([0-9])/(.+)', 'comment/update/$1');

// $c['router']->group(
//     array('domain' => 'test.demo_blog'), 
//     function ($group) use ($c) {
//         $c['router']->route('get', 'welcome/(.+)', 'tutorials/hello_scheme', null, $group);
//     }
// );
$c['router']->override('defaultController', 'welcome');
// $c['router']->override('pageNotFoundController', 'errors/page_not_found');


// Example Api

// $c['router']->group(
//     array('domain' => 'api.demo_blog'), 
//     function ($group) use ($c) {
//         $c['router']->route('get', 'user/create', 'api/user/create', null, $group);
//         $c['router']->route('get', 'user/delete/([0-9])', 'api/user/delete/$1', null, $group);
//     }
// );

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


/* End of file routes.php */
/* Location: .routes.php */