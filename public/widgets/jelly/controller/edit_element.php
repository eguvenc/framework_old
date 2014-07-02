<?php

/**
 * $app edit element
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
                $data = array();
                $data['id']        = $primaryKey;
                $data['form_id']   = $this->post['form_id'];
                $data['name']      = $this->post['name'];
                $data['title']     = $this->post['title'];
                $data['rules']     = $this->post['rules'];
                $data['label']     = $this->post['label'];
                $data['attribute'] = $this->post['attribute'];
                $data['type']      = $this->post['type'];
                $data['value']     = $this->post['value'];
                $data['order']     = $this->post['order'];
                $data['group_id']  = intval($this->post['group_id']);
                $data['role']      = $this->post['role'];

                $e = $this->db->transaction(
                    function () use ($data) {
                        $this->jellyForm->updateFormElement($data);
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('Form element successfully updated.', NOTICE_SUCCESS);
                }
            }
        }
        $this->jellyForm->load('save'); // Set widget "formSave"
        $fields = array_keys($this->jellyForm->getDefaultElementAttributes());
        array_unshift($fields, 'id', 'form_id', 'group_id', 'type', 'role');
        $elementData = $this->jellyForm->getFormElement($primaryKey, $fields);
        $form = $this->jellyForm->save->printElementEditForm(
            '/jelly/edit_element/'. $primaryKey,
            $elementData,
            array(
                'method'   => 'POST',
                'class'    => 'form-horizontal',
                'role'     => 'form',
                'name'     => 'saveForm',
            ),
            $elementData['form_id']
        );
        $this->jellyForm->load('lists', $elementData['form_id']); // Set new widget "formList"
        $tableList = $this->jellyForm->lists->printElementList('class="table table-bordered" id="data-table"');

        $this->view->load(
            'edit_element',
            function () use ($form, $tableList) {
                $this->assign('formData', $form);
                $this->assign('tableList', $tableList);
            }
        );
    }
);

/* End of file edit_element.php */
/* Location: .public/jelly/controller/edit_element.php */