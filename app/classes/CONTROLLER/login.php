<?php

/**
 * $c login
 * 
 * @category  Login
 * @package   Controller
 * @author    Ali İhsan ÇAĞLAYAN <ihsancaglayan@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs
 */
$app = new Controller(
    function ($c) {
        $c->load('auth');
        $c->load('request');
        $c->load('response');
        $c->load('translator');
        $this->userActive = new User_Active;
        $this->translator->load('membership/login');
        $this->translator->load('verify');
        if ($this->request->isXmlHttp() AND $this->auth->isLoggedIn() AND $this->userActive->isLoggedIn()) { // if user has already identity ?
            $json = new Json;
            $json->success(0);
            $json->key(Const_Key::POPUP_MESSAGE, $this->translator['FORM_ERROR:YOU_ARE_ALREADY_LOGGED_IN']);
            $json->encode();
            die;
        }
    }
);
$app->func(
    'index',
    function () use ($c) {

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json;charset=UTF-8');

        // $allowedHttpOrigin = array(
        //     // http
        //     'http://www.sporbahistir.com'     => 'sporbahistir',
        //     'http://sports.sporbahistir.com'  => 'sports',
        //     'http://live.sporbahistir.com'    => 'live',
        //     'http://games.sporbahistir.com'   => 'games',
        //     'http://test.com' => 'test.com',
        //     // https
        //     'https://www.sporbahistir.com'    => 'sporbahistir',
        //     'https://sports.sporbahistir.com' => 'sports',
        //     'https://live.sporbahistir.com'   => 'live',
        //     'https://games.sporbahistir.com'  => 'games',
        // );
        // if (isset($_SERVER['HTTP_ORIGIN']) AND isset($allowedHttpOrigin[$_SERVER['HTTP_ORIGIN']])) {
        //     header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        //     header('Access-Control-Allow-Credentials: true');
        //     header('Access-Control-Max-Age: 86400');    // cache for 1 day
        // }

        if ($this->request->isXmlHttp()) {
            $c->load('post');
            $c->load('form');
            $c->load('validator');
            // Callback password control
            $this->validator->func(
                'callback_password',
                function () {
                    $user_password  = $this->post['password'];
                    $password_regex = $this->config->load('user/global')['password_regex'];
                    if (preg_match($password_regex, $user_password)) {
                        return true;
                    }
                    $this->setMessage('callback_password', $this->translator['FORM_ERROR:YOUR_PASSWORD_IS_NOT_VALID']);
                    return false;
                }
            );
            $username = $this->post['username'];
            $password = $this->post['password'];

            $this->validator->setRules('username', $this->translator['FORM_LABEL:USERNAME'], 'required|min(6)|max(15)|alpha|callback_username');
            $this->validator->setRules('password', $this->translator['FORM_LABEL:PASSWORD'], 'required|min(8)|max(15)|callback_password');

            if ($this->validator->isValid()) {  // Form is valid ?
                $c->load('layer');
                $r = $this->layer->post('jsons/service.auth/query', array('username' => $username, 'password' => $password));
                if ($r[Const_Key::SUCCESS]) {      // If Login is Successful.
                    $row = $r[Const_Key::RESULTS]; // Get User Row
                    // Create new Guest                         // Get User Successful Login Data
                    $Guest = new Guest($username);              // Create new Guest with Success Token !
                    //------- Create Success Login Token -------//
                    if ($Guest->hasToken() == false) {          // If "Guest" has not got succesfull login token in Redis !
                        $Guest->getTemporaryToken();            // Create a temporary token which contains user auth data then
                        $Guest->setTemporaryCredentials($row);  // Give it to him.
                    }
                    // Execute User Status Class
                    $User_Status  = 'User_Status_' .ucfirst($row['user_status']);
                    $context      = array('username' => $row['user_username'],'user_id' => $row['user_id'], 'user_email' => $row['user_email'], 'ip' => $this->request->getIpAddress());
                    $Status       = new $User_Status($context);
                    $USER_STATUS_LOGGED_IN = $Status->formAction($this->form, $context);
                    $r[Const_Key::MESSAGE] = $Status->getMessage(); // Update form message with status
                    $auth   = $Guest->getTemporaryCredentials();
                    $Verify = new User_Verify_Code_Request(LOGIN);  // Send code to user
                    $Verify->setUsername($auth['user_data']['username']);
                    $Verify->setPhoneStatus($auth['user_data']['phone_status']);
                    $Verify->setVerifyTypeId($auth['user_data']['verify_type_id']);
                    /**
                     * LOGIN STEPS
                     * 
                     * 1 - User status is active or not ?
                     * 2 - User has verify ?
                     * 3 - System verify enabled ?
                     * 
                     * New User "user_is_verify" option is always = "0".
                     */
                    if ($Status->getStatus() == 'pending') {
                        $this->form->success(0);
                        $this->form->setKey(Const_Key::REDIRECT, '/membership/before_verify_email');
                        $this->form->setErrors($this->validator);
                        echo $this->response->json($this->form->outputArray());
                        return;
                    }
                    if ($Status->getStatus() == 'active' AND $Guest->hasVerify($row) AND $Verify->isEnabled()) {
                        if ($Verify->isMobilePhoneStatusPassive()) { // If user mobile passive WE redirect USER to Mobile Phone Check In PAGE.
                            $this->form->setKey(Const_Key::SUCCESS, 0);
                            $this->form->setKey(Const_Key::REDIRECT, '/membership/before_verify_mobile');
                            $this->form->setErrors($this->validator);
                            echo $this->response->json($this->form->outputArray());
                            return;
                        }
                        $Verifier = $Verify->factory();
                        $Verifier->formAction($this->form); // set verify popup
                        $Verifier->setMobilePhone($auth['user_data']['full_mobile_phone']);

                        if ($Verifier->createNewVerifyCode()) {
                            if ($Verifier->getVerifyType() == 'Sms') {
                                $Verifier->setTemplate('login.tpl');
                            }
                            if ($Verifier->sendCode()) {
                                /**
                                 * $userMobile user mobile censorship
                                 * @var string
                                 */
                                $userMobile = str_replace(substr($auth['user_data']['full_mobile_phone'], 3, 5), '*****', $auth['user_data']['full_mobile_phone']);
                                $this->form->setMessage($this->translator->sprintf('FORM_NOTICE:VERIFICATION_CODE_HAS_BEEN_SENT', $userMobile));
                                $this->form->setKey(Const_Key::NEXT_STEP, Const_Key::VERIFY_CODE_POPUP);
                                $this->form->setKey(Const_Key::SUCCESS, 2);
                            } else {
                                $this->form->setMessage($this->translator['FORM_ERROR:VERIFY_CODE_CANNOT_BE_SENT'], false);
                                $this->form->setKey(Const_Key::NEXT_STEP, Const_Key::LOGIN_SCREEN_POPUP);
                            }
                        } else {
                            $formError = new Form_Error(LOGIN, $Verifier->getErrorCode(), $this->form);
                            $formError->setError(); // Start set error
                            $this->form->setMessage($this->translator->sprintf($Verifier->getMessage()), false);
                        }
                    } elseif ($USER_STATUS_LOGGED_IN === true) {  // Verify the request token stored in memory container
                        $Login = new User_Login_Controller($this->form, $Guest->getUsername());
                        $Login->successfulAjaxLogin();
                        $this->form->setKey(Const_Key::SUCCESS, 1);
                    } else {
                        if ($row['user_status'] != 'pending') {
                            $this->form->setKey(Const_Key::NEXT_STEP, Const_Key::LOGIN_SCREEN_POPUP);
                        }
                        $this->form->setMessage($this->translator->sprintf($r[Const_Key::MESSAGE]), false); // This is set all messages to form.
                        $this->form->setKey(Const_Key::SUCCESS, 0);
                    }
                    $this->form->setErrors($this->validator);
                    echo $this->response->json($this->form->outputArray());
                    return;
                } else {
                    $this->form->setKey(Const_Key::NEXT_STEP, Const_Key::LOGIN_SCREEN_POPUP);
                }
                //  Set last error to form
                $this->form->setKey(Const_Key::SUCCESS, 0);
                $this->form->setMessage($this->translator[$r[Const_Key::MESSAGE]]); // This is set all messages to form.
            } else {
                $this->form->setKey(Const_Key::NEXT_STEP, Const_Key::LOGIN_SCREEN_POPUP);
            }
            // Finally print all Output
            $this->form->setErrors($this->validator);
            echo $this->response->json($this->form->outputArray());
            return;
        }
        return false;
    }
);

/* End of file login.php */
/* Location: .public/membership/controller/login.php */