<?php

/**
 * $c Header Controller
 *
 * @var View Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('view');
    }
);

$app->func(
    'index',
    function () {

        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->request->globals('uri')->getUriString().'</span></pre>';
        echo '<p>-----------------------------------------</p>';

        // var_dump($this->router->fetchDirectory());

        // $firstSegment   = $this->request->globals()->uri->segment(0);     // Get first segnment
        // $currentSegment = (empty($firstSegment)) ? 'home' : $firstSegment;  // Set current segment as "home" if its empty

        // $li = '';
        // $navbar = array(
        //     'home'    => 'Home',
        //     'about'   => 'About', 
        //     'contact' => 'Contact',
        //     'membership/login'   => 'Login',
        //     'membership/signup'  => 'Signup',
        // );
        // foreach ($navbar as $key => $value) {
        //     $active = ($currentSegment == $key) ? ' id="active" ' : '';
        //     $li.= '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
        // }

        $li = null;
        // Output must be string;


        /*
        echo $this->view->load(
            'header',
            function () use ($li) {
                $this->assign('li', $li);
            },
            false
        );
        */
    }
);

/* End of file header.php */
/* Location: .public/views/controller/header.php */