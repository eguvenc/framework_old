<?php

/**
 * $app hello_form
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('view');
        $c->load('jelly/form');
        $c->load('user');
    }
);

$app->func(
    'index',
    function () {
        header('Content-Type: Text/html; Charset=utf8');
        echo '<pre>';

        $this->user->setUserId(2);
        $this->user->setRoleId(2);
        $this->user->setResourceId('admin/user/edit');
        $test = $this->user->hasObjectPermission(array('username','email','firstname'), 'update');

        var_dump($test);

        die;
        $this->user->setUserId(1);
        $this->user->setResourceId('management/user');
        $this->user->setRoleId(1);
        $this->jellyForm->setForm(array('name' => 'register'), $this->user);
        $this->jellyForm->setValue(array('user_firstname' => 'Ali Ihsan', 'user_surname' => 'CAGLAYAN', 'day' => '01'), $this->user);

        print_r($this->jellyForm->render());

        print_r($this->jellyForm->toJson());

        // $this->jellyForm->widget('formList');
        // print_r($this->jellyForm->formList->printElementList(array('method' => 'POST')));

        // echo $this->jellyForm->formSave->printSaveForm(array('method' => 'POST'));


        
    }
);
