<?php

/**
 * $app add group
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
    function ($formId = '') {
        if ($this->post['submit']) {
            $this->validator->setRules('form_id', 'Form', 'required|isNumeric');
            $this->validator->setRules('name', 'Group Name', 'required|callback_name');
            $this->validator->func(
                'callback_name',
                function () {
                    $this->db->prepare(
                        'SELECT * FROM %s WHERE name = ?',
                        array(
                            $this->jellyForm->groupTableName
                        )
                    );
                    $this->db->bindValue(1, $this->post['name'], PARAM_STR);
                    $this->db->execute();

                    if ($this->db->count() > 0) {
                        $this->setMessage('callback_name', 'Input name has already taken.');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->validator->isValid()) {
                $data = array();
                $data['form_id'] = $this->post['form_id'];
                $data['name']    = $this->post['name'];
                $data['label']   = $this->post['label'];
                $data['value']   = $this->post['value'];
                $data['class']   = $this->post['class'];
                $data['func']    = $this->post['func'];
                $data['order']   = $this->post['order'];
                $e = $this->db->transaction(
                    function () use ($data) {
                        $this->jellyForm->insertFormGroup($data);
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('Form group successfully added.', NOTICE_SUCCESS);
                }
            }
        }
        $this->jellyForm->load('save'); // Set widget "formSave"
        $form = $this->jellyForm->save->printGroupSaveForm(
            '/jelly/add_group/' . $formId,
            '',
            array(
                'method'   => 'POST',
                'class'    => 'form-horizontal',
                'role'     => 'form',
                'name'     => 'saveForm',
            ),
            $formId
        );
        $this->jellyForm->load('lists', $formId); // Set new widget "formList"
        $tableList = $this->jellyForm->lists->printGroupList('class="table table-bordered" id="data-table"');
        
        $this->view->load(
            'add_group', 
            function () use ($form, $tableList) {
                $this->assign('formData', $form);
                $this->assign('tableList', $tableList);
            }
        );
        
    }
);

/* End of file add_group.php */
/* Location: .public/jelly/controller/add_group.php */