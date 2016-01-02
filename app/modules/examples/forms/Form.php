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

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('hobbies[]', 'Hobbies', 'callback_hobbies');
            $this->validator->setRules('agreement', 'User Agreement', 'required');
            $this->validator->callback(
                'callback_hobbies',
                function ($field) {

                    $value = $field->getValue();

                    // $min = new \Obullo\Validator\Rules\Min;
                    // $field->setParams(array(3));
                    // return $min($field);

                    if (empty($value)) {
                        $field->setMessage('Please choose a hobby.');
                        $field->setError('Please choose a hobby.');
                        return false;
                    }
                    return $field(); // call next
                }
            );
            if ($this->validator->isValid()) {          
                $this->form->success('Form validation success.');
            } else {
                $this->form->error('Form validation failed.');
            }
            $this->form->setErrors($this->validator);
        }

        $this->view->load('form');
    }
    
}