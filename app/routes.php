<?php
/*
|--------------------------------------------------------------------------
| Routes 
|--------------------------------------------------------------------------
| Typically there is a one-to-one relationship between a URL string and its 
| corresponding ( directory / controller / method ).
|
*/
$router->configure(
    [
        'domain' => 'framework',
        'defaultPage' => 'welcome',
    ]
);

// $router->get(
//     '{id}/{name}/{any}', 'welcome/index/$1/$2/$3',
//     function ($id, $name, $any) {
//         echo $id.'-'.$name.'-'.$any;
//     }
// )->where(array('id' => '[0-9]+', 'name' => '[a-z]+', 'any' => '.*'));

// $router->get('[0-9]+/.*', 'welcome/index/$1/$2/');

// $router->get(
//     '{id}/{name}/{any}', 'welcome/index/$1/$2/$3',
//     function ($id, $name, $any) use ($c) {
//         echo $id.'-'.$name.'-'.$any;
//     }
// )->where(['id' => '[0-9]+', 'name' => '[a-z]+', 'any' => '.+']);

// $router->middleware('Maintenance');

// $router->group(
//     [
//         'domain' => 'test.*\d.framework',
//     ],
//     function () {

//         // $this->get('[0-9]+/.*', 'welcome/index/$1/$2');

//         // $this->attach('.*'); // all urls
//         // $this->attach('welcome');
//         // $this->attach('welcome/test');
//     }
// );

// $router->get(
//     'welcome/[0-9]+/[a-z]+', 'welcome/$1/$2',
//     function () use ($c) {
//         $container->get('view')->load('views::dummy');
//     }
// )->attach('welcome/.*',  array('activity')); 

// $router->attach('.', array('maintenance'));

/**
 * Generic users
 */
$router->group(
    [
        // 'match' => '[0-9]+/[a-z]+.*',   // Match URI
        'domain' => 'framework',
        'middleware' => array()  // 'RewriteLocale'
    ],
    function ($sub) use ($container) {

        // $this->match(['get', 'post'], 'widgets/tutorials/helloForm')->middleware('Csrf');

        // $this->get('(?:en|de|es|tr)', 'welcome');     // example.com/en
        // $this->get('(?:en|de|es|tr)(/)', 'welcome');  // example.com/en/
        // $this->get('(?:en|de|es|tr)/(.*)', '$1');     // example.com/en/examples/helloWorld

        // $this->attach('.*'); // all urls
    }
);

/**
 * Authorized users
 */
$router->group(
    [
        'middleware' => array('Auth', 'Guest')
    ],
    function () {

        $this->attach('membership/restricted');

        // $this->get('tutorials/helloWorld.*', 'tutorials/helloLayout');
        // $this->attach('(.*)'); // all url
        // $this->attach('((?!tutorials/helloWorld).)*$');  // url not contains "tutorials/hello_world"
    }
);

// $router->error404('errors/page_not_found');

// Example Api


// $router->match(array('get', 'post'), 'welcome', 'welcome/test');