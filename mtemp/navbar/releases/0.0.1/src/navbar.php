<?php

/*
|--------------------------------------------------------------------------
| Navigation top bar class properties.
|--------------------------------------------------------------------------
| 
| Customize your css file navigation items :
|
| .navbar-top-active { }
| .navbar-top-inactive { }
| .navbar-sub-active { }
| .navbar-sub-inactive { }
|
*/
$navbar['top_active_class']   = 'navbar-top-active';
$navbar['top_inactive_class'] = 'navbar-top-inactive';
$navbar['sub_active_class']   = 'navbar-sub-inactive';
$navbar['sub_inactive_class'] = 'navbar-sub-inactive';

/*
|--------------------------------------------------------------------------
| Top Levels
|--------------------------------------------------------------------------
|
| $navbar['toplevel'][]['users']    = array('label' => 'Members', 'url' => 'users/list_all');
| $navbar['toplevel'][]['articles'] = array('label' => 'Articles', 'url' => 'articles/list_all');
|
*/
$navbar['toplevel'][]['users'] = array('label' => '', 'url' => '');

/*
|--------------------------------------------------------------------------
| Sub Levels
|--------------------------------------------------------------------------
|  
| $navbar['sublevel']['users'][]    = array('key' => 'save', 'label' => 'Add Member', 'url' => 'users/save');
| $navbar['sublevel']['articles'][] = array('key' => 'save', 'label' => 'Add Article','url' => 'articles/save');
*/
$navbar['sublevel']['users'][] = array('key' => '', 'label' => '', 'url' => '');