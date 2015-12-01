<?php

namespace Examples\Forms;

use Obullo\Http\Controller;

class Ajax extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('password', 'Password', 'required|min(6)');
            $this->validator->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->validator->setRules('agreement', 'User Agreement', 'required|exact(1)');

            if ($this->validator->isValid()) {          

                // isValid Errors.
                // 
                // $this->validator->setError('email', 'Custom Error: There is an error in email field !');
                // $this->form->error('There are some errors in form fields.');

                $this->form->success('Form validation success.');
                return $this->response->html($this->form->getMessage());

            } else {
                $this->form->error('Form validation failed.');
                $this->form->setErrors($this->validator);
            }

            $this->response->html($this->createForm());
            return;
        }
        $this->view->load(
            'ajax',
            [
                'html' => $this->createForm()
            ]
        );
    }

    /**
     * Create form html
     * 
     * @return string
     */
    public function createForm()
    {
        $html = $this->form->getMessage();

         $html.= '<form style="width: 300px;" role="form" action="/examples/forms/ajax" method="POST" id="ajaxForm">';
          
             $html.= '<div class="form-group '.$this->form->getErrorClass('email').'">';
             $html.= $this->form->getErrorLabel('email');
             $html.= '<input type="email" name="email" value="'.$this->form->getValue('email').'" class="form-control" id="email" placeholder="Email">';
             $html.= '</div>';

             $html.= '<div class="form-group '.$this->form->getErrorClass('password').' ">';
             $html.= $this->form->getErrorLabel('password');
             $html.= '<input type="password" name="password" class="form-control" id="pwd" placeholder="Password">';
             $html.= '</div>';

             $html.= '<div class="form-group '.$this->form->getErrorClass('confirm_password').'">';
             $html.= $this->form->getErrorLabel('confirm_password');
             $html.= '<input type="password" name="confirm_password" class="form-control" id="pwd" placeholder="Confirm Password">';
             $html.= '</div>';

             $html.= '<div class="form-group '.$this->form->getErrorClass('agreement').'">';
                $html.= '<div class="checkbox">';
                $html.= '<label><input type="checkbox" name="agreement" value="1" '.$this->form->setCheckbox('agreement', 1).' id="agreement"> I agree terms and conditions</label>';
                $html.= '</div>';
             $html.= '</div>';

            $html.= '<button type="submit" class="btn btn-default" onclick="submitAjax(\'ajaxForm\');">Submit</button>';
        $html.= '</form>';

        return $html;
    }

}