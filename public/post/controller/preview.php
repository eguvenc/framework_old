<?php

/**
 * $c preview
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        // $c->load('date_format');
        $c->load('tag/cloud');
        $c->load('form');
        $c->load('get');
        $c->load('view');
        $c->load('hvc');
    }
);

$c->func(
    'index.Private_User',
    function ($id) {

        $posts    = $this->hvc->get('private/posts/getone/'.$id);  // get one post
        $comments = $this->hvc->get('private/comments/getall/'.$id.'/1'); // get active post comments 

        $this->view->get(
            'preview',
            function () use ($posts, $comments) {
                $this->set('post', (object)$posts['results']);
                $this->set('comments', $comments['results']);
                $this->set('title', 'Preview');
                $this->getScheme();
            }
        );
    }
);

/* End of file preview.php */
/* Location: .public/home/controller/preview.php */