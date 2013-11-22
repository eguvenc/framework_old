<?php

/**
 * $c hello_odm
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new Model('user', 'users');

    new Url;
    new Html;
});

$c->func('index', function() use($c){  

    $c->view('hello_odm', function() use($c) {
        $this->set('name', 'Obullo');
        $this->set('footer', $c->tpl('footer', false));
    });

});

$c->func('doPost', function() use($c){

    $get = new Get;
    $this->user->email    = $get->post('email');
    $this->user->password = $get->post('password');

    // set non schema rules
    $this->user->setRule('confirm_password', array('rules' => 'required|matches(password)'));
    $this->user->setRule('agreement', array('label' => 'User Agreement', 'rules' => '_int(1)|required'));

    $this->user->func('save', function() {
        if ($this->isValid())
        {
            $this->password = md5($this->values('password'));
            return $this->db->insert('users', $this);
        }
        return false;
    });

    if($this->user->save())
    {        
        $this->user->setNotice('User saved successfully.');
        $this->url->redirect('tutorials/hello_odm');
    }

    $c->view('hello_odm');
});

/* End of file hello_odm.php */
/* Location: .public/tutorials/controller/hello_odm.php */