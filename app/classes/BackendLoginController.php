<?php

/**
 * Login Controller
 *
 * @category  Login
 * @package   Controller
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali İhsan ÇAĞLAYAN <ihsancaglayan@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/container
 */
Class User_Login_Controller
{
    const USER_PASSWORD   = 'password';
    const USER_IDENTIFIER = 'username';

    /**
     * User identifier
     * 
     * @var string
     */
    public $identifier = '';

    /**
     * Http_Request_Limiter class object
     * 
     * @var object
     */
    public $listenerIp = null;

    /**
     * Http_Request_Limiter class object
     * 
     * @var object
     */
    public $listenerUser = null;

    /**
     * Message
     * 
     * @var string
     */
    public $message = '';

    /**
     * User ip address
     * 
     * @var string
     */
    public $ipAddress = '';

    /**
     * Username column name
     * 
     * @var string
     */
    public $columnUsername = 'username';

    /**
     * Password column name
     * 
     * @var string
     */
    public $columnPassword = 'password';

    /**
     * Credentials
     * 
     * @var array
     */
    public $credentials = array();

    /**
     * Constructor
     *
     * @param array $params parameters
     */
    public function __construct($params = array())
    {
        global $c;
        $this->c = $c;
        if (count($params) > 1) {
            $this->columnUsername = $params['db.username'];
            $this->columnPassword = $params['db.password'];
        }
        $this->logger = $c->load('logger');
        $this->logger->channel(PANEL_USER); // Set channel
        $this->ipAddress = $c->load('request')->getIpAddress();
        $this->form = $c->load('form');

        // $attempt = System_Config::getSystemConfig('LOGIN', 'ATTEMPT');
        // $this->ipConfig = json_decode($attempt['IP'], true);
        // $this->identifierConfig = json_decode($attempt['USERNAME'], true);
    }

    /**
     * Set message
     * 
     * @param string $message message
     * 
     * @return void
     */
    protected function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Set user credentials
     * 
     * @param array $credentials user identifier
     * 
     * @return void
     */
    public function setCredentials($credentials = array())
    {
        $this->credentials = $credentials;
    }

    /**
     * Set hash (db password value)
     * 
     * @param string $hash hash value
     * 
     * @return void
     */
    protected function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * Get credentials
     * 
     * @param string $key credentials key
     * 
     * @return string
     */
    public function getCredentials($key)
    {
        return (isset($this->credentials[$key])) ? $this->credentials[$key] : null;
    }

    /**
     * Get hash
     * 
     * @return string
     */
    protected function getHash()
    {
        return $this->hash;
    }

    /**
     * Get message
     * 
     * @return string message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Is user ip banned
     * 
     * @return boolean
     */
    public function userIpIsBanned()
    {
        // global $c;
        // $this->listenerIp = $c->load('return rate/listener/ip');
        
        // if ( ! $this->listenerIp->isAllowed()) {

        //     $this->setMessage('FORM_ERROR:IP_BAN');

        //     $this->logger->notice(
        //         'Invalid login attempt user banned from auth service.',
        //         array(
        //             'category' => 'failedLoginAttemptsBlock',
        //             'data' => array(
        //                 'ip'   => $this->ipAddress,
        //                 'form' => array(
        //                     'username' => $this->getCredentials(self::USER_IDENTIFIER)
        //                 )
        //             )
        //         )
        //     );
        //     // $this->logger->push('mongo');
        //     return true;
        // }
        return false;
    }

    /**
     * Is user banned.
     * 
     * @return boolean
     */
    public function userIsBanned()
    {
        // global $c;
        // // $this->listenerUser = $c->load('return rate/listener/user', array('Login', $this->getCredentials(self::USER_IDENTIFIER)));
        // $this->listenerUser = $c->load('return rate/listener/user');
        // $this->listenerUser->setID($this->getCredentials(self::USER_IDENTIFIER));
        
        // if ( ! $this->listenerUser->isAllowed()) {

        //     $this->setMessage('FORM_ERROR:USERNAME_BAN');

        //     $this->logger->notice(
        //         'Invalid login attempt username is banned from auth service.',
        //         array(
        //             'category' => 'failedLoginAttemptsBlock',
        //             'data' => array(
        //                 'username' => $this->getCredentials(self::USER_IDENTIFIER),
        //                 'ip' => $this->ipAddress,
        //                 'form' => array(
        //                     'username' => $this->getCredentials(self::USER_IDENTIFIER)
        //                 )
        //             )
        //         )
        //     );
        //     // $this->logger->push('mongo');
        //     return true;
        // }
        return false;
    }

    /**
     * Validate credentials
     * 
     * @param mixed $row user identifier data
     * 
     * @return boolean
     */
    public function validateCredentials($row)
    {
        if ($row === false) {

            $this->setMessage('FORM_ERROR:WRONG_USERNAME');
            // $this->listenerIp->reduceLimit(); // Attempt IP
            
            // if ( ! $this->listenerIp->isAllowed()) {
            //     $this->setMessage('FORM_ERROR:IP_BAN');
            //     $this->logger->notice(
            //         'Invalid login attempt user banned from auth service.',
            //         array(
            //             'category' => 'failedLoginAttemptsBlock',
            //             'data' => array(
            //                 'ip'   => $this->ipAddress,
            //                 'form' => array(
            //                     'username' => $this->getCredentials(self::USER_IDENTIFIER)
            //                 )
            //             )
            //         )
            //     );
            //     // $this->logger->push('mongo');
            //     return false;
            // }
            $this->logger->notice(
                'Login attempt wrong username.',
                array(
                    'category' => 'failedLoginAttempts',
                    'data'     => array(
                        'ip'   => $this->ipAddress,
                        'form' => array(
                            'username' => $this->getCredentials(self::USER_IDENTIFIER)
                        )
                    )
                )
            );
            // $this->logger->push('mongo');
            return false;
        }
        if ( ! isset($row[self::USER_PASSWORD])) {
            return false;
        }
        $this->setHash($row[self::USER_PASSWORD]);

        if ($this->verifyPassword() === false) {
            return $this->wrongPassword();
        }
        return true;
    }

    /**
     * Verify password
     * 
     * @return boolean
     */
    public function verifyPassword()
    {
        $password = $this->c->load('return service/password');
        $hash = $this->getHash();

        if ($password->verify($this->getCredentials(self::USER_PASSWORD), $hash)) { // Verify User Password

            if ($password->needsRehash($this->getCredentials(self::USER_PASSWORD))) {

                $newHash     = $password->hash($this->getCredentials(self::USER_PASSWORD));
                $credentials = $this->getCredentials(self::USER_IDENTIFIER);
                $this->db    = $this->c->load('return service/provider/database', 'db');

                $columnPassword = $this->columnPassword;
                $columnUsername = $this->columnUsername;

                $e = $this->db->transaction(
                    function () use ($newHash, $columnPassword, $columnUsername, $credentials) {
                        $this->prepare(
                            'UPDATE users SET %s = ? WHERE %s = ?',
                            array(
                                $this->protect($columnPassword),
                                $this->protect($columnUsername)
                            )
                        );
                        $this->bindValue(1, $newHash, PARAM_STR);
                        $this->bindValue(2, $credentials, PARAM_STR);
                        $this->execute();
                    }
                );
                if (is_object($e)) {
                    $this->setMessage('FORM_ERROR:SOMETHING_WENT_WRONG');
                    return false;
                } else {
                    $this->logger->info(
                        'User entered password correctly and hash password successfully changed.',
                        array(
                            'category' => 'successLogin',
                            'data' => array(
                                'ip'   => $this->ipAddress,
                                'form' => array(
                                    'username' => $this->getCredentials(self::USER_IDENTIFIER)
                                )
                            )
                        )
                    );
                    // $this->logger->push('mongo');
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Wrong password
     * 
     * @return boolean
     */
    public function wrongPassword()
    {
        // $this->listenerIp->reduceLimit();    // Attempt IP
        // $this->listenerUser->reduceLimit();  // We have a right username, so we need also
        //                                      // run login attempt control for "Username"
        // if ( ! $this->listenerUser->isAllowed()) {
            
        //     $this->setMessage('FORM_ERROR:USERNAME_BAN');
        //     $this->logger->notice(
        //         'Invalid login attempt user banned from auth service.',
        //         array(
        //             'category' => 'failedLoginAttemptsBlock',
        //             'data' => array(
        //                 'ip'   => $this->ipAddress,
        //                 'form' => array(
        //                     'username' => $this->getCredentials(self::USER_IDENTIFIER)
        //                 )
        //             )
        //         )
        //     );
        //     // $this->logger->push('mongo');
        //     return false;
        // }
        $this->setMessage('FORM_ERROR:WRONG_PASSWORD');
        $this->logger->notice(
            'Login attempt wrong password.',
            array(
                'category' =>  'failedLoginAttempts',
                'data' => array(
                    'ip'   => $this->ipAddress,
                    'form' => array(
                        'username' => $this->getCredentials(self::USER_IDENTIFIER)
                    )
                )
            )
        );
        // $this->logger->push('mongo');
        return false;
    }

    /**
     * Successfull Ajax Login
     *
     * Sets ajax form keys and creates authorized
     * user.
     * 
     * @return void
     */
    public function successfulAjaxLogin()
    {
        $user = new User($this->getCredentials(self::USER_IDENTIFIER));    // Generate Login Credentials
        $user->authorize();

        /**
         * This class should be run after the authorize
         * because of we regenerate the session id
         * after authorization of the user.
         * 
         * We use the new regenerated session id
         * in User_Active class.
         * 
         * @var User_Active
         */
        $userActive = new User_Active($this->getCredentials(self::USER_IDENTIFIER)); // Add user to active members
        $userActive->addMember();

        $this->form->setKey(Const_Key::REDIRECT, '/');
    }
}

// END Contoller class

/* End of file Contoller.php */
/* Location: .classes/Service/Contoller.php */