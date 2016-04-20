<?php
/*
|--------------------------------------------------------------------------
| Routes 
|--------------------------------------------------------------------------
| Typically there is a one-to-one relationship between a URL string and its 
| corresponding ( folders / controller / method ).
|
*/
/**
 * Set your root domain without ".www" e.g."example.com"
 */
$router->setDomainRoot('framework');
$router->setSubfolderLevel(3);

// $router->get(
//     '{name}/{id}/{any}', 'welcome/index/$1/$2/$3',
//     function ($name, $id, $any) {
//         echo $name.'-'.$id.'-'.$any;
//     }
// )
// ->where(['id' => '[0-9]+', 'name' => '[a-z]+', 'any' => '.+'])
// ->add('Maintenance');


// $router->group(
//  'welcome',
//     function () {

//         // $this->get('[0-9]+/.*', 'welcome/index/$1/$2');

//         // $this->attach('.*'); // all urls
//         // $this->attach('welcome');
//         // $this->attach('welcome/test');
//     }
// );

/**
 * Default route
 */
$router->match(
    ['get', 'post'],
    '/', 'welcome',
    function () use ($container) {
        // echo 'ok';
    }
);
// ->add('Maintenance');

$router->get('welcome')->add('Guest');

// print_r($router->getRoute()->getAll());

// $router
//     ->domain('test.*\d.framework')
//     ->group(
//         function () {

//             $this->get('/', 'welcome');


//             //     // print_r($this->group->getOptions());

//             // $this->get(
//             //  'examples', function () {
//             //  echo 'ok';
//             // });

//             $this->group(
//                 'examples/', function () {

//                     // echo 'EXAMPLES';

//                     $this->group(
//                         'forms/', function () {

//                             // echo 'FORMS';
    
//                             $this->group(function(){});


//                         }
//                     )->add('Auth')->add('Guest')->attach('.*');

//                 }
//             );

//             // $this->match(['get', 'post'], 'widgets/tutorials/helloForm')->middleware('Csrf');

//             // $this->get('(?:en|de|es|tr)', 'welcome');     // example.com/en
//             // $this->get('(?:en|de|es|tr)(/)', 'welcome');  // example.com/en/
//             // $this->get('(?:en|de|es|tr)/(.*)', '$1');     // example.com/en/examples/helloWorld
//         }
//     )
//     ->add('Maintenance')->attach('.*');

// echo $router->getDomain()->getName();

// foreach ($router->getRoute()->getAll() as $key => $value) {
//     foreach ($value as $r) {
//         unset($r['closure']);
//     }
//     print_r($r);
// };


// /**
//  * Authorized users
//  */
// $router
//     ->domain('framework')
//     ->group(
//         function () {

//             // print_r($this->group->getOptions());

//             // $this->attach('membership/restricted');

//             // $this->get('tutorials/helloWorld.*', 'tutorials/helloLayout');
//             // $this->attach('.*'); // all url
//         }
//     )->add(['Guest'])->attach('.*')
//     ->end();


/**
 * Çalışmıyor.
 */
// $router->get('welcome/')->add('Guest')->attach('welcome/.*')

/**
 * Çalışmıyor.
 */
// ->attach(['((?!examples/helloWorld).)*$']); 

// ((?!examples/helloWorld).)*$  // url not contains "examples/hello_world"

// Example Api

// $router->match(array('get', 'post'), 'welcome', 'welcome/test');