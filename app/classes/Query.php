<?php

/**
 * $c auth/query
 * 
 * @var Private Controller
 */
$app = new Controller(
    function ($c) {  
        $c->load('post');
        $c->load('db');
        $c->load('auth');
        $c->load('service/password');
    }
);
$app->func(
    'index',
    function () use ($c) {
        $this->logger->channel(USER); // Set channel
        $username  = $this->post['username'];
        $ipAddress = $this->request->getIpAddress();
        $r = array(
            Const_Key::SUCCESS => 0,  // Default SUCCESS = "0"
        );
        $attempt = System_Config::getSystemConfig('LOGIN', 'ATTEMPT');
        $attemptIp = json_decode($attempt['IP'], true);
        $attemptUsername = json_decode($attempt['USERNAME'], true);

        //------------ IP BAN CONTROL ------------//
        // $ipListener = new Http_Request_Listener_Ip($ipAddress, LOGIN);
        // $ipListener->setIntervalLimit(
        //     $attemptIp['INTERVAL_LIMIT']['AMOUNT'],
        //     $attemptIp['INTERVAL_LIMIT']['LIMIT']
        // ); 
        // $ipListener->setHourlyLimit(
        //     $attemptIp['HOURLY_LIMIT']['AMOUNT'],
        //     $attemptIp['HOURLY_LIMIT']['LIMIT']
        // );
        // $ipListener->setDailyLimit(
        //     $attemptIp['DAILY_LIMIT']['AMOUNT'],
        //     $attemptIp['DAILY_LIMIT']['LIMIT']
        // );
        // $ipListener->setBanStatus($attemptIp['BAN']['STATUS']);
        // $ipListener->setBanExpiration($attemptIp['BAN']['EXPIRATION']);
        // $ipLimiter = new Http_Request_Limiter($ipListener);
        $c->load(
            'service/provider/listener/ip as listenerIp',
            array(
                'ip' => $ipAddress,
                'route' => LOGIN,
                'config' => $attemptIp,
            )
        );
        if ( ! $this->listenerIp->isAllowed()) {
            $r[Const_Key::MESSAGE] = i18n_Form_Error::IP_BAN;
            $this->logger->notice(
                'Invalid login attempt user banned from auth service.',
                array(
                    'category' => 'failedLoginAttemptsBlock',
                    'data' => array(
                        'username' => $username,
                        'ip_address' => $ipAddress,
                    )
                )
            );
            
            echo json_encode($r);
            return false;
        }
        
        //------------ USERNAME BAN CONTROL ------------//
        // $userListener = new Http_Request_Listener_Username($username, LOGIN);
        // $userListener->setIntervalLimit(
        //     $attemptUsername['INTERVAL_LIMIT']['AMOUNT'],
        //     $attemptUsername['INTERVAL_LIMIT']['LIMIT']
        // ); 
        // $userListener->setHourlyLimit(
        //     $attemptUsername['HOURLY_LIMIT']['AMOUNT'],
        //     $attemptUsername['HOURLY_LIMIT']['LIMIT']
        // );
        // $userListener->setDailyLimit(
        //     $attemptUsername['DAILY_LIMIT']['AMOUNT'],
        //     $attemptUsername['DAILY_LIMIT']['LIMIT']
        // );
        // $userListener->setBanStatus($attemptUsername['BAN']['STATUS']);
        // $userListener->setBanExpiration($attemptUsername['BAN']['EXPIRATION']);
        // $userLimiter = new Http_Request_Limiter($userListener);
        
        $c->load(
            'service/provider/listener/username as listenerUser',
            array(
                'username' => $username,
                'route' => LOGIN,
                'config' => $attemptUsername,
            )
        );

        if ( ! $this->listenerUser->isAllowed()) {
            $r[Const_Key::MESSAGE] = i18n_Form_Error::USERNAME_BAN;
            $this->logger->notice(
                'Invalid login attempt username is banned from auth service.',
                array(
                    'category' => 'failedLoginAttemptsBlock',
                    'data' => array(
                        'username' => $username,
                        'ip_address' => $ipAddress,
                    )
                )
            );

            echo json_encode($r);
            return false;
        }

        //-------- AUTH Secure Pdo Query -------//

        $this->db->prepare('SELECT * FROM users WHERE BINARY user_username = ?');
        $this->db->bindValue(1, $username, PARAM_STR);
        $this->db->execute();
        $row = $this->db->rowArray();

        //-------- WRONG USERMAME -------//

        if ($row === false) {
            $r[Const_Key::MESSAGE] = i18n_Form_Error::WRONG_USERMAME;
            $this->listenerIp->reduceLimit();   // Attempt IP
            
            if ( ! $this->listenerIp->isAllowed()) {
                $r[Const_Key::MESSAGE] = i18n_Form_Error::IP_BAN;
                $this->logger->notice(
                    'Invalid login attempt user banned from auth service.',
                    array(
                        'category' => 'failedLoginAttemptsBlock',
                        'data' => array(
                            'username' => $username,
                            'ip_address' => $ipAddress,
                        )
                    )
                );
                
                echo json_encode($r);
                return false;
            }
            $this->logger->notice(
                'Login attempt wrong username.',
                array(
                    'category' => 'failedLoginAttempts',
                    'data'     => array(
                        'username' => $username,
                        'ip_address' => $ipAddress,
                    )
                )
            );
            
            echo json_encode($r);
            return;
        }

        //-------- SUCCESS -------//
        if ($this->password->verify($this->post['password'], $row['user_password'])) { // Verify User Password 
            $r = array(
                Const_Key::SUCCESS => 1,
                Const_Key::MESSAGE => null,
                Const_Key::RESULTS => $row,
            );
            echo json_encode($r);  // auth success.
            return;
        }
        //-------- WRONG PASSWORD -------//
        $r[Const_Key::MESSAGE] = i18n_Form_Error::WRONG_PASSWORD;

        $this->listenerIp->reduceLimit();              // Attempt IP
        $this->listenerUser->reduceLimit();            // We have a right username, so we need also
                                                // run login attempt control for "Username"
        if ( ! $this->listenerUser->isAllowed()) {
            
            $r[Const_Key::MESSAGE] = i18n_Form_Error::USERNAME_BAN;
            $this->logger->notice(
                'Invalid login attempt user banned from auth service.',
                array(
                    'category' => 'failedLoginAttemptsBlock',
                    'data' => array(
                        'username' => $username,
                        'ip_address' => $ipAddress,
                    )
                )
            );
            
            echo json_encode($r);
            return false;
        }
        $this->logger->notice(
            'Login attempt wrong password.',
            array(
                'category' => 'failedLoginAttempts',
                'data' => array(
                    'username' => $username,
                    'ip_address' => $ipAddress,
                )
            )
        );
        
        echo json_encode($r);
    }
);