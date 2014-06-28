<?php

/**
 * $app add element
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
            $this->validator->setRules('name', 'Name', 'required|callback_name');
            $this->validator->setRules('type', 'Type', 'required|alpha');
            $this->validator->setRules('order', 'Order', 'required|isNumeric');
            $this->validator->func(
                'callback_name',
                function () {
                    //-- Only type radio allowed --//
                    if ($this->post['type'] == 'radio') {
                        return true;
                    }
                    $this->db->prepare(
                        'SELECT * FROM %s WHERE name = ? AND form_id = ?',
                        array(
                            $this->db->protect($this->jellyForm->elementTableName)
                        )
                    );
                    $this->db->bindValue(1, $this->post['name'], PARAM_STR);
                    $this->db->bindValue(2, $this->post['form_id'], PARAM_INT);
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
                $data['form_id']   = $this->post['form_id'];
                $data['type']      = $this->post['type'];
                $data['name']      = $this->post['name'];
                $data['title']     = $this->post['title'];
                $data['rules']     = $this->post['rules'];
                $data['label']     = $this->post['label'];
                $data['attribute'] = $this->post['attribute'];
                $data['value']     = $this->post['value'];
                $data['order']     = $this->post['order'];
                $data['group_id']  = intval($this->post['group_id']);
                $data['role']      = $this->post['role'];

                $e = $this->db->transaction(
                    function () use ($data) { // Database save operation
                        $this->jellyForm->insertFormElement($data);
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('Form element successfully added.', NOTICE_SUCCESS);
                }
            }
        }
        $this->jellyForm->load('save'); // Set widget "formSave"
        $form = $this->jellyForm->save->printElementSaveForm(
            '/jelly/add_element/' . $formId,
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
        $tableList = $this->jellyForm->lists->printElementList('class="table table-bordered" id="data-table"');
        
        $this->view->load(
            'add_element', 
            function () use ($form, $tableList) {
                $this->assign('formData', $form);
                $this->assign('tableList', $tableList);
            }
        );
        
    }
);

/* End of file add_element.php */
/* Location: .public/jelly/controller/add_element.php */