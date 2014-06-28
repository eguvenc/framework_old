<?php

/**
 * $app edit group
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
                $data['id']      = $primaryKey;
                $data['form_id'] = $this->post['form_id'];
                $data['name']    = $this->post['name'];
                $data['label']   = $this->post['label'];
                $data['value']   = $this->post['value'];
                $data['class']   = $this->post['class'];
                $data['func']    = $this->post['func'];
                $data['order']    = $this->post['order'];

                $e = $this->db->transaction(
                    function () use ($data) {
                        $this->jellyForm->updateFormGroup($data);
                    }
                );
                if (is_object($e)) {
                    $this->form->setMessage($e->getMessage(), NOTICE_ERROR);
                } else {
                    $this->form->setMessage('Form group successfully edited.', NOTICE_SUCCESS);
                }
            }
        }
        $this->jellyForm->load('save'); // Set widget "formSave"
        $groupData = $this->jellyForm->getFormGroup($primaryKey, 'form_id,name,label,class,value,func,order');
        $form = $this->jellyForm->save->printGroupEditForm(
            '/jelly/edit_group/'. $primaryKey,
            $groupData,
            array(
                'method'   => 'POST',
                'class'    => 'form-horizontal',
                'role'     => 'form',
                'name'     => 'saveForm',
            ),
            $groupData['form_id']
        );
        $this->jellyForm->load('lists', $groupData['form_id']); // Set new widget "formList"
        $tableList = $this->jellyForm->lists->printGroupList('class="table table-bordered" id="data-table"');

        $this->view->load(
            'edit_group', 
            function () use ($form, $tableList) {
                $this->assign('formData', $form);
                $this->assign('tableList', $tableList);
            }
        );
    }
);

/* End of file edit_group.php */
/* Location: .public/jelly/controller/edit_group.php */