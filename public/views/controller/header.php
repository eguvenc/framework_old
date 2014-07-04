<?php

/**
 * $c Header Controller
 *
 * @var Private View Controller
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
        $firstSegment   = $this->request->globals()->uri->segment(0);     // Get first segnment
        $currentSegment = (empty($firstSegment)) ? 'home' : $firstSegment;  // Set current segment as "home" if its empty

        $li = '';
        $navbar = array(
            'home'    => 'Home',
            'about'   => 'About', 
            'contact' => 'Contact',
            'membership/login'   => 'Login',
            'membership/signup'  => 'Signup',
        );
        foreach ($navbar as $key => $value) {
            $active = ($currentSegment == $key) ? ' id="active" ' : '';
            $li.= '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
        }

        // Output must be string;

        echo $this->view->load(
            'header',
            function () use ($li) {
                $this->assign('li', $li);
            },
            false
        );
    }
);

/* End of file header.php */
/* Location: .public/views/controller/header.php */