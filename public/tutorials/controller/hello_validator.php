<?php

/**
 * $c hello_validator
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new Get;
    new Url;
    new Html;
});

$c->func('index', function() use($c){  

    new Form;

    $c->view('hello_validator', function() use($c) {

        if($this->get->post('dopost'))
        {
            $this->form->setRules('email', 'Email', 'required|validEmail');
            $this->form->setRules('password', 'Password', 'required|minLen(6)');
            $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->form->setRules('agreement', 'User Agreement', '_int|required');

            if($this->form->run())
            {        
                $this->form->setNotice('Validation Success !');  // Set flash notice using Session Class.

                $this->url->redirect('tutorials/hello_validator'); // Redirect to user same page.
            }
        }

        $this->set('name', 'Obullo');
        $this->set('footer', $c->tpl('footer', false));

    });

});

/* End of file hello_validator.php */
/* Location: .public/tutorials/controller/hello_validator.php */