<?php
/*
|--------------------------------------------------------------------------
| Routes 
|--------------------------------------------------------------------------
| Typically there is a one-to-one relationship between a URL string and its 
| corresponding ( folders / controller / method ).
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
//     function () {

//         // $this->get('[0-9]+/.*', 'welcome/index/$1/$2');

//         // $this->attach('.*'); // all urls
//         // $this->attach('welcome');
//         // $this->attach('welcome/test');
//     }
//     ['domain' => 'test.*\d.framework'],
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
$router->domain('framework')
    ->group(
        'examples/',
        function () {

            // echo 'EXAMPLES';

            $this->group(
                'forms/', function () {
                    // echo 'FORMS';
                }
            );
            // $this->match(['get', 'post'], 'widgets/tutorials/helloForm')->middleware('Csrf');

            // $this->get('(?:en|de|es|tr)', 'welcome');     // example.com/en
            // $this->get('(?:en|de|es|tr)(/)', 'welcome');  // example.com/en/
            // $this->get('(?:en|de|es|tr)/(.*)', '$1');     // example.com/en/examples/helloWorld

            // $this->attach('.*'); // all urls
        },
        ['middleware' => array()]
);

/**
 * Authorized users
 */
$router->group(
    function () {

        // $this->attach('membership/restricted');

        // $this->get('tutorials/helloWorld.*', 'tutorials/helloLayout');
        // $this->attach('(.*)'); // all url
        // $this->attach('((?!tutorials/helloWorld).)*$');  // url not contains "tutorials/hello_world"
    },
    ['middleware' => array('Auth', 'Guest')]
);

// $router->error404('errors/page_not_found');

// Example Api


// $router->match(array('get', 'post'), 'welcome', 'welcome/test');