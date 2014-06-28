<?php

/**
 * $c Header Controller
 *
 * @var Private View Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('auth');
        $c->load('view');
    }
);

$app->func(
    'index',
    function () {

        // Note: Lvc View Controller output must be string !

        $comments = $this->private->get('comment/get_count')['results']['count'];

        echo $this->view->load(
            'sidebar',
            function () {
                if ($auth = $this->auth->isLoggedIn()) {
                    $this->assign('auth', $auth);
                    $this->assign('username', $this->auth->data('user_username'));
                    $this->assign('total_comments', $comments);
                }
            },
            false
        );
    }
);

/* End of file sidebar.php */
/* Location: .public/views/controller/sidebar.php */