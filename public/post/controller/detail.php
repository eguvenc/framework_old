<?php

/**
 * $c detail
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
        $c->load('view');
        $c->load('post');
        $c->load('hvc');
        // $c->load('private/user');
    }
);

$c->func(
    'index',
    function ($id) {
        if ($this->post->get('dopost')) {  // if we have submit

            $this->form->setRules('comment_name', 'Name', 'required');
            $this->form->setRules('comment_email', 'Email', 'required|validEmail');
            $this->form->setRules('comment_website', 'Website', 'required');
            $this->form->setRules('comment_body', 'Commend', 'required|xssClean');

            if ($this->form->isValid()) {
                
                $r = $this->lvc->post('private/comments/create/');

                if ($r['success']) {  // save comment
                    $this->form->setNotice($r['message'], SUCCESS);
                    $this->url->redirect($this->uri->getRequestUri());
                } else {
                    $this->form->setMessage($r['message']);
                }
            }
        }

        $post  = $this->hvc->get('private/posts/getone/'.$id.'/Published');
        $comments = $this->hvc->get('private/comments/getall/'.$id.'/1');

        $this->view->load(
            'detail',
            function () use ($post, $comments) {
                $this->assign('post', (object)$post['results']);
                $this->assign('comments', (object)$comments['results']);
                $this->assign('title', 'Details');
                $this->getScheme();
            }
        );
    }
);

/* End of file detail.php */
/* Location: .public/post/controller/detail.php */
