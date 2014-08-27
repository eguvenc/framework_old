<?php

/**
 * $app add form
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('db');
        $c->load('post');
        $c->load('form');
        $c->load('flash/session as flash');
        $c->load('service/jelly/form as jellyForm');
        $c->load('validator');
    }
);

$app->func(
    'index',
    function () {

        if ($this->post['submit']) {
            $this->validator->setRules('name', 'Form name', 'required|callback_name');
            $this->validator->func(
                'callback_name',
                function () {
                    $this->db->prepare(
                        'SELECT * FROM %s WHERE %s = ?',
                        array(
                            $this->db->protect($this->jellyForm->formTableName),
                            $this->db->protect($this->jellyForm->columnFormName)
                        )
                    );
                    $this->db->bindValue(1, $this->post['name'], PARAM_STR);
                    $this->db->execute();

                    if ($this->db->count() > 0) {
                        $this->setMessage('callback_name', 'Form name has already taken.');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->validator->isValid()) {
                $data                = array();
                $data['name']        = $this->post['name'];
                $data['resource_id'] = $this->post['resource_id'];
                $data['action']      = $this->post['action'];
                $data['attribute']   = $this->post['attribute'];
                $data['method']      = $this->post['method'];

                $e = $this->db->transaction(
                    function () use ($data) {
                        $this->jellyForm->insertForm($data);
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('Form has successfully added.', NOTICE_SUCCESS);
                }
            }
        }
        $this->jellyForm->load('save'); // Set widget "formSave"
        $form = $this->jellyForm->save->printSaveForm(
            '/jelly/add_form',
            '',
            array(
                'method' => 'POST',
                'class'  => 'form-horizontal',
                'role'   => 'form'
            )
        );
        $this->jellyForm->load('lists'); // Set new widget "formList"
        $tableList = $this->jellyForm->lists->printList('class="table table-bordered" id="data-table"');

        $this->view->load(
            'add_form', 
            function () use ($form, $tableList) {
                $this->assign('formData', $form);
                $this->assign('tableList', $tableList);
            }
        );
    }
);

/* End of file add_form.php */
/* Location: .public/jelly/controller/add_form.php */