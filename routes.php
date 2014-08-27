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

$c['router']->domain($c->load('config')['url']['root']);  // Root domain

// $c['router']->route(
//     '*', 'welcome/([0-9]+)/([a-z]+)', 'welcome/$1/$2', 
//     function () use ($c) {
//         $c->load('view')->load('dummy');
//     }
// );

// $c['router']->route('get', 'tutorials/hello_world(.*)', 'tutorials/hello_scheme');


$c['router']->attach('(.*)', array('filters' => array('before.maintenance', 'before.auth'))); 

// $c['router']->group(
//     array('name' => 'default', domain' => 'framework'),
//     function ($group) {
//         $this->route('*', 'jelly/(.+)', 'widgets/jelly/$1', null, $group);  // Rewrite "jform" uri to widgets/ folders
//         $this->route('get', '(en|tr)/(.+)', '$2', null, $group, null, $group);
//         $this->route('get', '(en|tr)', 'welcome/index', null, $group);  // default controller
//         $this->route('get', 'tag/(.+)', 'tag/$1', null, $group);
//         $this->route('get', 'post/detail/([0-9])', 'post/detail/$1', null, $group);
//         $this->route('get', 'post/preview/([0-9])', 'post/preview/$1', null, $group);
//         $this->route('get', 'post/update/([0-9])', 'post/update/$1', null, $group);
//         $this->route('post', 'comment/delete/([0-9])', 'comment/delete/$1', null, $group);
//         $this->route('post', 'comment/update/([0-9])/(.+)', 'comment/update/$1', null, $group);
//     }
// );


// $c['router']->group(
//     array('name' => 'maintenance_test' ,'domain' => $c['config']->xml->app->test->domain->regex, 'filters' => array('before.maintenance', 'before.auth')), 
//     function ($group) {
//         // $this->route('get', 'tutorials/hello_world.*', 'tutorials/hello_scheme', null, $group);
//         $this->attach('welcome.*', $group); // all url
//         // $this->attach('((?!tutorials/hello_world).)*$', $group);  // url not contains "tutorials/hello_world"
//     }
// );

$c['router']->defaultPage('welcome');
// $c['router']->error404('errors/page_not_found');

// Example Api

// $c['router']->group(
//     array('domain' => 'api.demo_blog'), 
//     function ($group) {
//         $this->route('get', 'user/create', 'api/user/create', null, $group);
//         $this->route('get', 'user/delete/([0-9])', 'api/user/delete/$1', null, $group);
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