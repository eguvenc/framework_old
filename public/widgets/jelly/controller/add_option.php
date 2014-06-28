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
        $c->load('session/flash as flash');
        $c->load('service/jelly/form as jellyForm');
        $c->load('validator');
    }
);

$app->func(
    'index',
    function ($formId = '') {
        if ($this->post['submit']) {
            $this->validator->setRules('form_id', 'Form id', 'required|isNumeric');
            $this->validator->setRules('name', 'Option name', 'required|callback_name');
            $this->validator->setRules('value', 'Option value', 'required|isNumeric');
            $this->validator->func(
                'callback_name',
                function () {
                    $this->db->prepare(
                        'SELECT * FROM %s WHERE %s = ? AND %s = ?',
                        array(
                            $this->db->protect($this->jellyForm->optionTableName),
                            $this->db->protect($this->jellyForm->columnFormName),
                            $this->db->protect($this->jellyForm->columnFormId)
                        )
                    );
                    $this->db->bindValue(1, $this->post['name'], PARAM_STR);
                    $this->db->bindValue(2, $this->post['form_id'], PARAM_INT);
                    $this->db->execute();
                    
                    if ($this->db->count() > 0) {
                        $this->setMessage('callback_name', 'Option name has already taken.');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->validator->isValid()) {
                $data            = array();
                $data['form_id'] = $this->post['form_id'];
                $data['name']    = $this->post['name'];
                $data['value']   = $this->post['value'];
                $e = $this->db->transaction(
                    function () use ($data) {
                        $this->jellyForm->insertFormOption($data);
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('Option successfully added.', NOTICE_SUCCESS);
                }
            }
        }
        $this->jellyForm->load('save'); // Set widget "formSave"
        $form = $this->jellyForm->save->printOptionSaveForm(
            '/jelly/add_option/' . $formId,
            '',
            array(
                'method'   => 'POST',
                'class'    => 'form-horizontal',
                'role'     => 'form',
            ),
            $formId
        );
        $this->jellyForm->load('lists', $formId); // Set new widget "formList"
        $tableList = $this->jellyForm->lists->printOptionList('class="table table-bordered" id="data-table"');

        $this->view->load(
            'add_option', 
            function () use ($form, $tableList) {
                $this->assign('formData', $form);
                $this->assign('tableList', $tableList);
            }
        );
    }
);

/* End of file add_form.php */
/* Location: .public/jelly/controller/add_form.php */