<?php

/**
 * Bcrypt class
 *
 * Blowfish simple secure password hashing.
 *
 * @package       packages
 * @subpackage    bcyrpt
 * @category      encrypt
 * @link(Bcyrpt Class, https://github.com/cosenary/Bcrypt-PHP-Class)
 * 
 * @author        ported to Obullo by rabihsyw 
 * @copyright     Christian Metz - 2012
 */
Class Bcrypt
{
    private $_workFactor = 10;    // Work cost factor range between [04; 31]
    private $_identifier = '2y';  // Default identifier
    private $_validIdentifiers = array('2a', '2x', '2y'); // All valid hash identifiers

    /**
     * Constructor
     *
     * @access    public
     * @return    void
     */

    public function __construct()
    {
        global $logger;
        if (!isset(getInstance()->bcrypt)) {
            getInstance()->bcrypt = $this;  // Make available it in the controller.
        }
        $logger->debug('Bcrypt Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Hash the password
     *
     * @param string $password
     * @param integer $workFactor optional
     * @return string
     */
    public function hashPassword($password, $workFactor = 0)
    {
        $salt = $this->_generateSalt($workFactor);

        return crypt($password, $salt);
    }

    // --------------------------------------------------------------------

    /**
     * Check hash bcrypt password is correct ?
     *
     * @param string $password
     * @param string $storedHash
     * @return boolean true or false
     */
    public function verifyPassword($password, $storedHash)
    {
        $this->_validateIdentifier($storedHash);
        $checkHash = crypt($password, $storedHash);

        return ($checkHash === $storedHash);
    }

    // --------------------------------------------------------------------

    /**
     * Generates the salt string
     *
     * @param integer $workFactor
     * @return string
     */
    private function _generateSalt($workFactor)
    {
        if ($workFactor < 4 || $workFactor > 31) { // do not increase the work factor                                        // this may cause performance problems.
            $workFactor = $this->_workFactor;
        }
        $input = $this->_getRandomBytes();
        $salt = '$' . $this->_identifier . '$';
        $salt .= str_pad($workFactor, 2, '0', STR_PAD_LEFT);
        $salt .= '$';
        $salt .= substr(strtr(base64_encode($input), '+', '.'), 0, 22);
        return $salt;
    }

    // --------------------------------------------------------------------

    /**
     * OpenSSL's random generator
     *
     * @return string
     */
    private function _getRandomBytes()
    {
        if (!function_exists('openssl_random_pseudo_bytes')) {
            throw new Exception('Unsupported hash format.');
        }
        return openssl_random_pseudo_bytes(16);
    }

    // --------------------------------------------------------------------

    /**
     * Validate identifier
     *
     * @param string $hash
     * @return void
     */
    private function _validateIdentifier($hash)
    {
        if ( ! in_array(substr($hash, 1, 2), $this->_validIdentifiers)) {
            throw new Exception('Unsupported hash format.');
        }
    }

}

// END Bcyrpt Class

/* End of file bcrypt.php */
/* Location: ./packages/bcrypt/releases/0.0.1/bcrypt.php */