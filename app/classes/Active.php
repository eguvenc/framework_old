<?php

/**
 * User_Active
 * 
 * @category  User_Active
 * @package   Bet-Frontend
 * @author    Ali İhsan ÇAĞLAYAN <ihsancaglayan@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs
 */
Class User_Active
{
    // Redis prefix constant for active users
    const CACHE_KEY_PREFIX   = 'User_Active:';
    const CACHE_KEY_METADATA = 'metaData';
    const CACHE_KEY_USERDATA = 'userData';
    const REDIS_SESSION_KEY   = 'o2_session:';
    
    // Redis prefix constant for php session data
    const CURRENT_PAGE       = 'active_page';
    const LAST_LOGIN         = 'last_login';
    const LAST_ACTIVITY      = 'last_activity';
    const SESSION_ID         = 'session_id';

    public $auth   = null; // Auth Object
    public $sess   = null; // Sess Object
    public $cache  = null; // Cache Object
    public $config = null; // Config Object
    public $expiration;    // Expiration time
    public $userData;
    public $metaData = array(
        self::CURRENT_PAGE  => '',
        self::SESSION_ID    => '',
        self::LAST_LOGIN    => 0,
        self::LAST_ACTIVITY => ''
    );

    /**
     * Constructor
     * 
     * @param string $username username
     */
    public function __construct($username = '')
    {
        global $c;
        $this->c = $c;

        $this->sess    = $c->load('return session');
        $this->auth    = $c->load('return auth');
        $this->cache   = $c->load('return service/cache');
        $this->request = $c->load('return request');

        if (empty($username)) {
            $username = $this->auth->data('username');
        }
        $this->username = (string)$username;
        
        $c->load('config')->load('user/global');

        $config = $c->load('config')['session'];
        $this->expiration = $config['lifetime'];

        /**
         * Get redis key:
         * "User_Active:username:metaData"
         * 
         * @var string
         */
        $this->redisKeyMetaData = $this->getRedisKey() . static::CACHE_KEY_METADATA;
        $this->redisKeyUserData = $this->getRedisKey() . static::CACHE_KEY_USERDATA;

        /**
         * User_Active "userData"
         * Array
         * (
         *     [user_id]  => 1
         *     [username] => testUser
         *     [email]    => test@obullo.com
         *)
         * @var array
         */
        $this->userData = (empty($this->userData)) ? $this->cache->get($this->redisKeyUserData) : $this->userData;

        /**
         * User_Active "metaData"
         * Array
         * (
         *     [active_page]   => forgot_password
         *     [session_id]    => odfk9a4h5hnegavkvg32uncvb3
         *     [last_login]    => 1397895553
         *     [last_activity] => 1398930524
         *)
         * @var array
         */
        $this->metaData = (empty($this->metaData)) ? $this->cache->get($this->redisKeyMetaData) : $this->metaData;

        $this->logger = $c->load('logger');
        $this->logger->debug(__CLASS__ . ' Initialized');
        $this->logger->channel(USER);
    }

    /**
     * Get Key
     * 
     * Class name : username
     * User_Active:Artas:
     * 
     * @return string
     */
    public function getRedisKey()
    {
        return static::CACHE_KEY_PREFIX . $this->username . ':';
    }

    /**
     * Add a online user to redis or memcache memory
     * container using user "session_id" as a key see below the
     * memory key.
     *
     * prefix_$user_id = array()
     *
     *  Redis key : user_id
     *              -------
     * User_Active:98
     * 
     * Array
     * (
     *     [drh518acpvjo9jn05463m24ip7] => Array
     *     (
     *         [active_page] => 'forgot_password'
     *         [last_login] => '1392914518'
     *     )
     * )
     *
     * @return boolean true or false
     */
    public function addMember()
    {
        if (empty($this->username)) {
            return false;
        }
        $session_id = $this->sess->get(static::SESSION_ID); // get session_id
        $last_login = $this->sess->get(static::LAST_LOGIN);
        $redisKey   = $this->getRedisKey() . static::CACHE_KEY_METADATA;

        if ($this->metaData[static::SESSION_ID] != $session_id) {
            $this->deleteOldSession();
        }
        $this->logger->info(
            'User added to active members.',
            array(
                'category' => LOGIN,
                'data' => array(
                    'username_key' => $redisKey
                )
            )
        );
        $router = new Utilities_Router($this->c);
        
        $this->metaData = array(
            static::CURRENT_PAGE  => $router->getCurrentPage(),
            static::SESSION_ID    => $session_id,
            static::LAST_LOGIN    => $last_login,
            static::LAST_ACTIVITY => time()
        );
        return $this->cache->set($redisKey, $this->metaData, $this->expiration);
    }

    /**
     * Test function
     * Daha sonra silinecek.
     *
     * @return boolean
     */
    public function addMemberTest()
    {
        return;
    }
    
    /**
     * If a user multiple logged in from different browsers
     * we kill the old sessions and we accept the last new session.
     *          
     * @return boolean true or false
     */
    public function isLoggedIn()
    {
        if (empty($this->username)) {
            return false;
        }
        /**
         * User_Active "metaData"
         * Array
         * (
         *     [active_page]   => forgot_password
         *     [session_id]    => odfk9a4h5hnegavkvg32uncvb3
         *     [last_login]    => 1397895553
         *     [last_activity] => 1398930524
         *)
         * @var array
         */
        $this->metaData = $this->cache->get($this->redisKeyMetaData);
        /**
         * Get session_id
         * 
         * @var integer
         */
        $session_id = $this->sess->get(static::SESSION_ID);

        /**
        *  CHECK USER 
        *  
         * If a user multiple logged in from different browsers
         * we kill the old sessions and we accept the last new session.
         */
        if (isset($this->metaData[static::SESSION_ID]) AND $this->metaData[static::SESSION_ID] != $session_id) {  // If user not in active members
            $this->cache->delete(static:REDIS_SESSION_KEY . $session_id);  // remove from sessions and prevent duplication of memory
            $this->auth->logout();
            $this->logger->info(
                'The user has logged in with a different session id so the old session has expired.',
                array(
                    'category' => LOGIN,
                    'data' => array(
                        'user_id' => $this->data('user_id'),
                        'ip_address' => $this->request->getIpAddress(),
                        'user_id_key' => $this->redisKeyMetaData,
                        'old_session_id' => $session_id,
                        'active_session_id' => $this->metaData[static::SESSION_ID]
                    )
                )
            );
            
            return false;
        }

        /**
         * Last activity time of user
         * 
         * @var integer
         */
        $last_activity = (isset($this->metaData[static::LAST_ACTIVITY])) ? $this->metaData[static::LAST_ACTIVITY] : 0;
    
        /**
         * Active member lifetime INTEGER VALUE  = 1
         */
        $activeConfig = System_Config::getSystemConfig('USER', 'ACTIVE');
        $activity_time = strtotime('-'. $activeConfig['ACTIVITY_TIME'] .' hour');

        /**
         * REMOVE MEMBER FROM ACTIVE USERS
         * 
         * If user last activity time less than
         * config file acitivity time ( forexample if no activity for 1 hour )
         * destroy the session and remove member from active users.
         */
        if ($last_activity < $activity_time) {  
            $this->removeMember();
            $this->auth->logout();
            $this->logger->info(
                'No activity, user session has been expired at this time.',
                array(
                    'category' => USER_ACTIVE,
                    'data' => array(
                        'user_id'       => $this->username,
                        'ip_address'    => $this->request->getIpAddress(),
                        'user_id_key'   => $this->redisKeyMetaData,
                        'activity_time' => $activity_time,
                    )
                )
            );
            return false;
        }
        $current_page = (isset($this->userData[static::CURRENT_PAGE])) ? $this->userData[static::CURRENT_PAGE] : '';

        if ( ! $this->request->isXmlHttp()) {
            $router = new Utilities_Router($this->c);
            $current_page = $router->getCurrentPage();
        }
        /**
         *  UPDATE CURRENT PAGE INFO OF THE USER
         * 
         * $newData update "active_page"
         * 
         * @var array
         */
        $newData = array(
            static::CURRENT_PAGE  => $current_page,
            static::SESSION_ID    => $session_id,
            static::LAST_LOGIN    => $this->userData[static::LAST_LOGIN],
        );
        $this->cache->set($this->redisKeyMetaData, $newData, $this->expiration);
        return true;
    }

    /**
     * Update activity time
     * 
     * @return void
     */
    public function updateActivityTime()
    {
        $sessionId = $this->sess->get(static::SESSION_ID);

        if (empty($this->username) OR $sessionId != $this->metaData[static::SESSION_ID]) {
            return false;
        }
        $current_page = (isset($this->userData[static::CURRENT_PAGE])) ? $this->userData[static::CURRENT_PAGE] : '';

        if ( ! $this->request->isXmlHttp()) {
            $router = new Utilities_Router($this->c);
            $current_page = $router->getCurrentPage();
        }
        $newData = array(
            static::CURRENT_PAGE  => $current_page,
            static::SESSION_ID    => $sessionId,
            static::LAST_LOGIN    => (isset($this->userData[static::LAST_LOGIN])) ? $this->userData[static::LAST_LOGIN] : 0,
            static::LAST_ACTIVITY => time()
        );
        $this->cache->set($this->redisKeyMetaData, $newData, $this->expiration);
    }

    // /**
    //  * Get authenticated user session data
    //  * 
    //  * @param string $key string
    //  * 
    //  * @return If have data return mixed, otherwise false.
    //  */
    // public function data($key = '')
    // {
    //     if (empty($key) OR $this->userData === false) {
    //         return false;
    //     }
    //     return (isset($this->userData[$key])) ? $this->userData[$key] : false;
    // }

    /**
     * "Set" authenticated user session data
     * 
     * @param mixed $key  key or data
     * @param mixed $data user data or expiration time
     * 
     * @return mixed
     */
    public function setData($key, $data = null)
    {
        $redisKey = $this->getRedisKey() . static::CACHE_KEY_USERDATA;
        $userData = $this->cache->get($redisKey);
        if (empty($key)) {
            return false;
        }
        if (is_array($key)) {
            $newData = array_merge($userData, $key);
            $expiration = (is_numeric($data)) ? $data : $this->expiration;

            return $this->cache->set($redisKey, $newData, $expiration);
        }
        if ($data !== null) {
            $expiration = (int)$this->expiration;
            $userData[$key] = $data;

            return $this->cache->set($redisKey, $userData, $expiration);
        }
        return false;
    }

    /**
     * Get all user data
     * 
     * @return array user data otherwise false
     */
    public function getAllData()
    {
        $redisKey = $this->getRedisKey() . static::CACHE_KEY_USERDATA;
        return $this->cache->get($redisKey);
    }

    /**
     * Unset session auth data from user session container
     * 
     * @param string $key string
     * 
     * @return void
     */
    public function remove($key)
    {
        $redisKey = $this->getRedisKey() . static::CACHE_KEY_USERDATA;
        if (is_array($key)) {
            foreach ($key as $val) {
                $this->cache->delete($redisKey. $val);
            }
            return;
        }
        $this->cache->delete($redisKey. $key);
    }

    /**
     * Make credentials
     * 
     * @param array $data user data
     * 
     * @return void
     */
    // public function makeCredentials($data = array())
    // {
    //     $key = $this->getRedisKey() . static::CACHE_KEY_USERDATA;
    //     $this->cache->set($key, $data, $this->expiration);
    // }

    /**
     * Delete Old Session
     *
     * If we have Multiple login wşth same identifier
     * we delete the old session.
     * 
     * @return void
     */
    protected function deleteOldSession()
    {
        $redisKey = $this->getRedisKey();
        $oldMetaData     = $this->cache->get($redisKey . static::CACHE_KEY_METADATA);
        $activeSessionId = $this->sess->get(static::SESSION_ID);

        if (isset($oldMetaData[static::SESSION_ID]) AND $activeSessionId != $oldMetaData[static::SESSION_ID]) {

            $this->cache->delete(static::REDIS_SESSION_KEY . $oldMetaData[static::SESSION_ID]);
            $this->logger->info(
                'The user has logged in, old sessions has expired.',
                array(
                    'category' => LOGIN,
                    'data' => array(
                        'user_id' => $this->username,
                        'ip_address' => $this->request->getIpAddress(),
                        'active_session_id' => $activeSessionId,
                        'old_session_id'    => $oldMetaData[static::SESSION_ID]
                    )
                )
            );
            
        }
    }

    /**
     * Remove user_active_member data in the cache
     * 
     * @return boolean true or false
     */
    public function removeMember()
    {
        $sessionId = $this->sess->get(static::SESSION_ID);

        /**
         * "COOKIE_AUTH" use the javascript
         * 
         * @var Cookie object
         */
        $cookie = $this->c->load('cookie');
        $cookie->set(Const_Key::COOKIE_AUTH, 0);
        $this->auth->logout();
        if ($sessionId != $this->metaData[static::SESSION_ID]) {
            return;
        }
        // Delete from redis
        // "User_Active:username:*" get all keys
        // Now defined keys "metaData" and "userData"
        $keys = $this->cache->getAllKeys($this->getRedisKey() . '*');
        $this->cache->delete($keys);
    }
}

// END User_Active Class

/* End of file User_Active.php */
/* Location: .classes/user/active.php */