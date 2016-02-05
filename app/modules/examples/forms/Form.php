<?php

namespace Examples\Forms;

use Obullo\Http\Controller;

class Form extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            // $min = new \Obullo\Validator\Rules\Min;
            // $field->getRule()->setParams(array(3));
            // return $min($field);

            // var_dump($this->request->post('options'));

            $this->validator->setRules('options[]', 'Options', 'callback_options');
            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('hobbies[]', 'Hobbies', 'callback_hobbies');
            $this->validator->setRules('agreement', 'User Agreement', 'required');
            $this->validator->callback(
                'callback_options',
                function ($field, $next) {
                    $value = $field->getValue();
                    if (empty($value)) {
                        $field->setMessage('Please choose a color.');
                        $field->setError('Please choose a color.');
                        return false;
                    }
                    return $next($field);
                }
            );
            $this->validator->callback(
                'callback_hobbies',
                function ($field, $next) {
                    $value = $field->getValue();
                    if (empty($value)) {
                        $field->setMessage('Please choose a hobby.');
                        $field->setError('Please choose a hobby.');
                        return false;
                    }
                    return $next($field);
                }
            );
            if ($this->validator->isValid()) {          
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
        }

        $this->view->load('form');
    }
    
}