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
    function ($primaryKey) {
        if ($this->post['submit']) {
            $this->validator->setRules('form_id', 'Form id', 'required|isNumeric');
            $this->validator->setRules('name', 'option name', 'required');
            $this->validator->setRules('value', 'Option value', 'required');
            if ($this->validator->isValid()) {
                $data            = array();
                $data['id']      = $primaryKey;
                $data['form_id'] = $this->post['form_id'];
                $data['name']    = $this->post['name'];
                $data['value']   = $this->post['value'];

                $e = $this->db->transaction(
                    function () use ($data) {
                        $this->jellyForm->updateFormOption($data);
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('Option successfully updated.', NOTICE_SUCCESS);
                }
            }
        }        
        $this->jellyForm->load('save'); // Set widget "formSave"
        $optionData = $this->jellyForm->getFormOption($primaryKey, 'id,form_id,name,value');
        $formId = $optionData['form_id'];
        unset($optionData['form_id']);
        $form = $this->jellyForm->save->printOptionEditForm(
            '/jelly/edit_option/' . $primaryKey,
            $optionData,
            array(
                'method' => 'POST',
                'class'  => 'form-horizontal',
                'role'   => 'form',
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