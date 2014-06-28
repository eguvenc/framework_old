<?php

/**
 * $c update
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        $c->load('form');
        $c->load('view');
        $c->load('post');
        $c->load('hvc');
        $c->load('db');
    }
);

$c->func(
    'index.Private_User',
    function ($id) {

        if ($this->post->get('dopost')) { // if do post click
            
            $this->form->setRules('post_title', 'Title', 'required');
            $this->form->setRules('post_content', 'Content', 'required|xssClean');
            $this->form->setRules('post_status', 'Status', 'required');

            if ($this->form->isValid()) {  // update post
                $r = $this->hvc->post('private/posts/update/'.$id, array('user_id' => $this->auth->getIdentity('user_id')));

                if ($r['success']) {
                    $this->form->setNotice($r['message'], SUCCESS);
                    $this->url->redirect('post/update/'. $id);
                } else {
                    $this->form->setMessage($r['message']);
                }
            }
        }

        $row = $this->hvc->get('private/posts/getone/'.$id);

        if (count($row['results']) == 0) {
            $this->response->show404(); // record not found
        }

        $this->view->get(
            'update',
            function () use ($id, $row) {
                $this->set('title', 'Update Post');
                $this->set('post_id', $id);
                $this->set('row', (object) $row['results']);  // send as object
                $this->getScheme();
            }
        );
    }
);