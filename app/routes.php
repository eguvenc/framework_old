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

$c['router']->configuration(
    [
        'domain' => 'framework',
        'defaultPage' => 'welcome',
        'error404' => null
    ]
);

// $c['router']->middleware('Maintenance');

$c['router']->group(
    [
        'name' => 'test',
        // 'domain' => '^framework$',
        //'middleware' => array('Maintenance')
    ],
    function () {

        // $this->attach('.*'); // all urls
        // $this->attach('welcome');
        // $this->attach('welcome/test');
    }
);
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
        'namespace' => 'Welcome',
        'domain' => '^framework$',
        'middleware' => array('Maintenance')
    ],
    function ($sub) use ($c) {

            // echo 'OK !!';

            // $this->match(['get', 'post'], 'widgets/tutorials/helloForm')->middleware('Csrf');

            // $this->get('(?:en|tr|de|nl)/(.*)', '$1');
            // $this->get('(?:en|tr|de|nl)', 'welcome');  // default controller

            $this->attach('.*'); // all urls
    }
);


$c['router']->group(
    [
        'name' => 'AuthorizedUsers',
        'middleware' => array('Auth', 'Guest')
    ],
    function () {

        // $this->defaultPage('welcome');
        // $this->attach('membership/restricted');

        // $this->get('tutorials/helloWorld.*', 'tutorials/helloLayout');
        // $this->attach('(.*)'); // all url
        // $this->attach('((?!tutorials/helloWorld).)*$');  // url not contains "tutorials/hello_world"
    }
);

// $c['router']->error404('errors/page_not_found');

// Example Api


// $c['router']->match(array('get', 'post'), 'welcome', 'welcome/test');