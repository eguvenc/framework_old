<?php

/**
 * $c about
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        $c->load('view');
        $c->load('hvc');
    }
);
$c->func(
    'index',
    function () {
        $this->view->get(
            'about',
            function () {
                $this->set('title', 'About');
                $this->getScheme();
            }
        );
    }
);

/* End of file about.php */
/* Location: .public/about/controller/about.php */