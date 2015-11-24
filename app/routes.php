<?php
/*
|--------------------------------------------------------------------------
| Routes 
|--------------------------------------------------------------------------
| Typically there is a one-to-one relationship between a URL string and its 
| corresponding ( directory / controller / method ).
|
*/
$c['router']->configure(
    [
        'domain' => 'framework',
        'defaultPage' => 'welcome',
    ]
);

$c['router']->get('([0-9]+)/(.*)', 'welcome/index/$1/$2');

// $c['router']->get(
//     '{id}/{name}/{any}', 'welcome/index/$1/$2/$3',
//     function ($id, $name, $any) use ($c) {
//         echo $id.'-'.$name.'-'.$any;
//     }
// )->where(['id' => '([0-9]+)', 'name' => '([a-z]+)', 'any' => '(.+)']);

// $c['router']->middleware('Maintenance');

$c['router']->group(
    [
        'domain' => 'test.*\d.framework',
    ],
    function () {

        $this->get('([0-9]+)/(.*)', 'welcome/index/$1/$2');

        // $this->attach('.*'); // all urls
        // $this->attach('welcome');
        // $this->attach('welcome/test');
    }
);

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
        // 'match' => '([0-9]+)/([a-z]+).*',   // Match URI
        'domain' => 'framework',
        'middleware' => array('Maintenance')
    ],
    function ($sub) use ($c) {

            // $this->match(['get', 'post'], 'widgets/tutorials/helloForm')->middleware('Csrf');

            // echo $this->getNamespace();

            // echo 'OK !!';

            // $this->get('(?:en|tr|de|nl)/(.*)', '$1');
            // $this->get('(?:en|tr|de|nl)', 'welcome');  // default controller

            // $this->attach('.*'); // all urls

            // $this->middleware(['Maintenance'], '.*');
            // ikinci parametre girilmemişse
            // tek olarak atayacak middleware i girilirse
            // regex vardır diyecek.

    }
);


$c['router']->group(
    [
        'name' => 'AuthorizedUsers',
        'middleware' => array('Auth', 'Guest')
    ],
    function () {

        // $this->attach('membership/restricted');

        // $this->get('tutorials/helloWorld.*', 'tutorials/helloLayout');
        // $this->attach('(.*)'); // all url
        // $this->attach('((?!tutorials/helloWorld).)*$');  // url not contains "tutorials/hello_world"
    }
);

// $c['router']->error404('errors/page_not_found');

// Example Api


// $c['router']->match(array('get', 'post'), 'welcome', 'welcome/test');