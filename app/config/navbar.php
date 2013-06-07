<?php
defined('BASE') or exit('Access Denied!');
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
| $navbar['top_level'][]['users']    = array('label' => 'Members', 'url' => 'backend/users/list_all');
| $navbar['top_level'][]['articles'] = array('label' => 'Articles', 'url' => 'backend/articles/list_all');
|
*/
$navbar['top_level'][]['users'] = array('label' => '', 'url' => '');

/*
|--------------------------------------------------------------------------
| Sub Levels
|--------------------------------------------------------------------------
|  
| $navbar['sub_level']['users'][]    = array('key' => 'save', 'label' => 'Add Member', 'url' => 'backend/users/save');
| $navbar['sub_level']['articles'][] = array('key' => 'save', 'label' => 'Add Article','url' => 'backend/articles/save');
*/
$navbar['sub_level']['users'][] = array('key' => '', 'label' => '', 'url' => '');