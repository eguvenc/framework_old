<?php
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
| Check user is logged in or not.
*/
$c['router']->createFilter(
    'auth',
    function () use ($c) {
        $c->load('auth');
        
        if ($this->auth->isGuest()) {
            $cookieData = $this->cookie->get(Const_Key::COOKIE_AUTH);
            if ($cookieData == 1) {
                $this->cookie->set(Const_Key::COOKIE_AUTH, 0);
            }
            if ($c->load('request')->isXmlHttp()) {
                $json = new Json;
                $json->success(0);
                $json->message($c->load('translator')->load('membership/login')['FORM_ERROR:YOUR_SESSION_HAS_EXPIRED']);
                $json->key(Const_Key::NEXT_STEP, Const_Key::LOGIN_SCREEN_POPUP);
                $json->encode();
                die;
            }
            $c->load('url')->redirect('/'); // redirect user to login page
        }
    }
);
/*
|--------------------------------------------------------------------------
| Unique Session
|--------------------------------------------------------------------------
| Single sign on ( One user can login using one unique session on one browser )
*/
$c['router']->createFilter(
    'unique.session',
    function () use ($c) {
        $this->userActive = new User_Active;
        if ( ! $this->userActive->isLoggedIn()) {
            $c->load('auth')->logout();
            if ($c->load('request')->isXmlHttp()) {
                $json = new Json;
                $json->success(0);
                $json->message($c->load('translator')->load('membership/login')['FORM_ERROR:YOUR_SESSION_HAS_EXPIRED']);
                $json->key(Const_Key::NEXT_STEP, Const_Key::LOGIN_SCREEN_POPUP);
                $json->encode();
                die;
            }
            $c->load('url')->redirect('/'); // redirect user to login page
        }
    }
);
/*
|--------------------------------------------------------------------------
| Csrf
|--------------------------------------------------------------------------
| Cross Site Request Forgery Filter
*/
$c['router']->createFilter(
    'update.lastActivity',
    function () use ($c) {
        if ($c->load('auth')->isLoggedIn()) {  // normal auth check
            $this->userActive = new User_Active;
            $this->userActive->updateActivityTime();
        }
    }
);