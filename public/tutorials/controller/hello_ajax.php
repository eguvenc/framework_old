<?php

/**
 * $c hello_ajax
 * @var Controller
 */
$c = new Controller(function(){});

$c->func('index', function() use($c){

    new Html;
    new Url;

    $c->view('hello_ajax');
});

$c->func('dopost', function(){

    new Model('user', 'users');

    $get = new Get;
    $this->user->email = $get->post('email');
    $this->user->password = $get->post('password');

    $this->user->setRule('confirm_password', array('rules' => 'required|matches(password)'));
    $this->user->setRule('agreement', array('label' => 'User Agreement', 'rules' => '_int(1)|required'));

    $this->user->func('save',function() { 
        if($this->isValid())
        {
            $this->password = md5($this->values('password'));
            return $this->db->insert('users', $this);
        }
        return false;
    });

    $this->user->save();

    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json;charset=UTF-8');

    echo json_encode($this->user->output());
});

/* End of file hello_ajax.php */
/* Location: .public/tutorials/controller/hello_ajax.php */