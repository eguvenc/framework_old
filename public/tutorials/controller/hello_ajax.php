<?php

/**
 * $c hello_ajax
 * @var Controller
 */
$c = new Controller(function(){
    // _construct
});

$c->func('index', function() use($c){

    new Html;
    new Url;
    new Form;

    $c->view('hello_ajax');
});

$c->func('dopost', function(){

    new Get;
    new Model('user', 'users');

    $this->user->email    = $this->get->post('email');
    $this->user->password = $this->get->post('password');

    //--------------------- set non schema rules
    
    $this->user->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
    $this->user->setRules('agreement', 'User Agreement', '_int|required|exactLen(1)');
    
    //---------------------

    $this->user->func('save', function() { 
        if($this->isValid())
        {
            $this->password = md5($this->getValue('password'));
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