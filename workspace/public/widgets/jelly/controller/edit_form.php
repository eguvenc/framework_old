<?php

/**
 * $app edit form
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('db');
        $c->load('post');
        $c->load('form');
        $c->load('session/flash as flash');
        $c->load('service/jelly/form as jellyForm');
        $c->load('validator');
    }
);

$app->func(
    'index',
    function ($primaryKey) {
        if ($this->post['submit']) {
            $this->validator->setRules('name', 'Name', 'required');
            if ($this->validator->isValid()) {
                $data                = array();
                $data['id']          = $primaryKey;
                $data['name']        = $this->post['name'];
                $data['resource_id'] = $this->post['resource_id'];
                $data['action']      = $this->post['action'];
                $data['attribute']   = $this->post['attribute'];
                $data['method']      = $this->post['method'];

                $e = $this->db->transaction(
                    function () use ($data) {
                        $this->jellyForm->updateForm($data);
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('Form successfully updated.', NOTICE_SUCCESS);
                }
            }
        }
        $this->jellyForm->load('save'); // Set widget "formSave"
        $form = $this->jellyForm->save->printEditForm(
            '/jelly/edit_form/'. $primaryKey,
            $this->jellyForm->getForm($primaryKey, 'name,resource_id,action,attribute,method'),
            array(
                'method' => 'POST',
                'class'  => 'form-horizontal',
                'role'   => 'form',
            )
        );
        $this->jellyForm->load('lists'); // Set new widget "formList"
        $tableList = $this->jellyForm->lists->printList('class="table table-bordered" id="data-table"');
        
        $this->view->load(
            'edit_form', 
            function () use ($form, $tableList) {
                $this->assign('formData', $form);
                $this->assign('tableList', $tableList);
            }
        );
    }
);

/* End of file edit_form.php */
/* Location: .public/jelly/controller/edit_form.php */