<?php

namespace Examples\Membership;

use Obullo\Http\Controller;

class Restricted extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $html = '<html>';
        $html.= '<head>';
        $html.= '</head><body>';

        $html.= '<h1>Restricted Area</h1>';

        $html.= $this->flash->output();

        $html.= '<section>';

        $html.= '<a href="/examples/membership/logout">Logout</a>';
        $html.= '<pre>';
        $html.= print_r($this->user->identity->getArray(), true);
        $html.= '</pre>';

        $html.= '</section>';
        $html.= '</body>';
        $html.= '</html>';

        $this->response->html($html);

    }
}