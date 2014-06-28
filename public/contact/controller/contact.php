<?php

/**
 * $c contact
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        $c->load('form');
        $c->load('hvc');
        $c->load('post');
        $c->load('view');
    }
);

$c->func(
    'index.Public_User',
    function () {

        if ($this->post->get('dopost')) {

            $this->form->setRules('contact_name', 'Name', 'required');
            $this->form->setRules('contact_email', 'Email', 'required|validEmail');
            $this->form->setRules('contact_subject', 'Subject', 'required');
            $this->form->setRules('contact_body', 'Body', 'required|xssClean');

            if ($this->form->isValid()) {
                $r = $this->hvc->post('private/contacts/create');

                if ($r['success']) {
                    $this->form->setNotice($r['message'], SUCCESS);
                    $this->url->redirect('/contact');
                } else {
                    $this->form->setMessage($r['message']);
                }
            }
        }

        $this->view->get(
            'contact',
            function () {
                $this->set('title', 'Contact');
                $this->getScheme();
            }
        );

    }
);