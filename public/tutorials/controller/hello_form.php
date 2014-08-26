<?php

/**
 * $app hello_form
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('html');
        $c->load('view');
        $c->load('post');
        $c->load('form');
        $c->load('session as sess');
        $c->load('flash/session as flash');
    }
);

$app->func(
    'index',
    function () use ($c) {

        $errors = array();
        $errorString = null;

        if ($this->post['dopost']) {
            $c->load('validator');
            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required');

            $this->validator->func(
                'callback_test',
                function ($email, $val) {
                    if (strlen($email) < $val) {
                        $this->setMessage('callback_test', 'Callback function validation test error !');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->validator->isValid()) {

                // $this->validator->setError('email', 'Example Error !');                
                $this->form->setMessage('Example form success message in current page !', NOTICE_SUCCESS);
                
                var_dump($this->form->status());

                // $this->sess->setFlash('notice', 'Example flash notice please refresh this page !');   // Set flash notice using Session Class.
                // $this->url->redirect('tutorials/hello_form/index'); // Redirect to user same page using header refresh.

            } else {
                $this->form->setMessage('Form validation failed !', NOTICE_ERROR);
            }
            $errors = $this->validator->getErrors();
            $errorString = $this->validator->getErrorString();
        }

        $this->view->load(
            'hello_form', 
            function () use ($errors, $errorString) {
                $this->assign('name', 'Obullo');
                $this->assign('errors', $errors);
                $this->assign('errorString', $errorString);
                $this->assign('footer', $this->template('footer', false));
            }
        );

    }
);

/* End of file hello_form.php */
/* Location: .public/tutorials/controller/hello_form.php */