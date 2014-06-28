<?php

/**
 * $c tag
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        // $c->load('date_format');
        $c->load('tag/cloud');
        $c->load('view');
        $c->load('hvc');
    }
);

$c->func(
    'index.Public_User',
    function ($tag) {

        $r = $this->private->get('posts/get_all_by_tag/'.$tag);

        $this->view->get(
            'tag',
            function () use ($r) {
                $this->set('title', 'Tagged Posts');
                $this->set('posts', $r['results']);
                $this->getScheme(); 
            }
        );
    }
);

/* End of file tag.php */
/* Location: .public/tag/controller/tag.php */