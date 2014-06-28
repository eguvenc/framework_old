<?php

/**
 * $app test_insert_all_elements
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('service/rbac/user');
        $c->load('form');
        $c->load('form/element');
        $c->load('post');
        $c->load('request');
        service/crud
        $c->load('validator');
        $c->load('service/jelly/form as jellyForm');
    }
);

$app->func(
    'index',
    function () {
        $this->user->setUserId(1);
        $this->user->setRoleIds($this->user->getRoles());
        $this->user->setResourceId('admin/user/add');
        
        $this->jellyForm->setId('insert_form');
        $this->jellyForm->setValues($this->post[true]);

        if ($this->request->isPost()) {

            if ( ! $this->jellyForm->validate('insert')) {

                $this->form->setErrors($this->validator);
                $this->form->setMessage('There are some errors in the form.');
                
            } else {
                $e = $this->db->transaction(
                    function () {
                        $this->db->insert('users', $this->jellyForm->getValues());
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('User successfully inserted.', NOTICE_SUCCESS);
                }
            }
        }
        if ($this->request->isXmlHttp()) { // Form is ajax post?
            echo $this->response->json($this->form->getOutput());
            return;
        }
        $this->jellyForm->render('insert'); // Create the form.
        $this->view->load('test_form');
    }
);


/* End of file test_insert_all_elements.php */
/* Location: .public/jform/controller/test_insert_all_elements.php */