<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo        
 * @author          obullo.com
 * @copyright       Ersin Guvenc (c) 2009.
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Encryption Class
 * 
 * Provides two-way keyed encoding using XOR Hashing and Mcrypt
 *
 * @package     Obullo
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Ersin Guvenc
 * @link        
 */
Class OB_Encrypt {

    public $encryption_key    = '';
    public $_hash_type        = 'sha1';
    public $_mcrypt_exists    = FALSE;
    public $_mcrypt_cipher;
    public $_mcrypt_mode;
    
    /**
    * Constructor
    *
    * Simply determines whether the mcrypt library exists.
    *
    */
    public function __construct()
    {
        $this->_mcrypt_exists = ( ! function_exists('mcrypt_encrypt')) ? FALSE : TRUE;
        
        log_me('debug', "Encrypt Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
    * Fetch the encryption key
    *
    * Returns it as MD5 in order to have an exact-length 128 bit key.
    * Mcrypt is sensitive to keys that are not the correct length
    *
    * @access   public
    * @param    string
    * @return   string
    */
    public function get_key($key = '')
    {
        if ($key == '')
        {
            if ($this->encryption_key != '')
            {
                return $this->encryption_key;
            }

            $key = this()->config->item('encryption_key');

            if ($key === FALSE)
            {
                throw new Exception('In order to use the encryption class requires that you set 
                an encryption key in your config file.');
            }
        }

        return md5($key);
    }

    // --------------------------------------------------------------------

    /**
    * Set the encryption key
    *
    * @access   public
    * @param    string
    * @return   void
    */
    public function set_key($key = '')
    {
        $this->encryption_key = $key;
    }

    // --------------------------------------------------------------------

    /**
    * Encode
    *
    * Encodes the message string using bitwise XOR encoding.
    * The key is combined with a random hash, and then it
    * too gets converted using XOR. The whole thing is then run
    * through mcrypt (if supported) using the randomized key.
    * The end result is a double-encrypted message string
    * that is randomized with each call to this function,
    * even if the supplied message and key are the same.
    *
    * @access   public
    * @param    string    the string to encode
    * @param    string    the key
    * @return   string
    */
    public function encode($string, $key = '')
    {
        $key = $this->get_key($key);
        $enc = $this->_xor_encode($string, $key);
        
        if ($this->_mcrypt_exists === TRUE)
        {
            $enc = $this->mcrypt_encode($enc, $key);
        }
        return base64_encode($enc);
    }

    // --------------------------------------------------------------------

    /**
    * Decode
    *
    * Reverses the above process
    *
    * @access   public
    * @param    string
    * @param    string
    * @return   string
    */
    public function decode($string, $key = '')
    {
        $key = $this->get_key($key);
        
        if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string))
        {
            return FALSE;
        }

        $dec = base64_decode($string);

        if ($this->_mcrypt_exists === TRUE)
        {
            if (($dec = $this->mcrypt_decode($dec, $key)) === FALSE)
            {
                return FALSE;
            }
        }

        return $this->_xor_decode($dec, $key);
    }

    // --------------------------------------------------------------------

    /**
    * XOR Encode
    *
    * Takes a plain-text string and key as input and generates an
    * encoded bit-string using XOR
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   string
    */
    private function _xor_encode($string, $key)
    {
        $rand = '';
        while (strlen($rand) < 32)
        {
            $rand .= mt_rand(0, mt_getrandmax());
        }

        $rand = $this->hash_encode($rand);

        $enc = '';
        for ($i = 0; $i < strlen($string); $i++)
        {            
            $enc .= substr($rand, ($i % strlen($rand)), 1).(substr($rand, ($i % strlen($rand)), 1) ^ substr($string, $i, 1));
        }

        return $this->_xor_merge($enc, $key);
    }

    // --------------------------------------------------------------------

    /**
    * XOR Decode
    *
    * Takes an encoded string and key as input and generates the
    * plain-text original message
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   string
    */
    private function _xor_decode($string, $key)
    {
        $string = $this->_xor_merge($string, $key);

        $dec = '';
        for ($i = 0; $i < strlen($string); $i++)
        {
            $dec .= (substr($string, $i++, 1) ^ substr($string, $i, 1));
        }

        return $dec;
    }

    // --------------------------------------------------------------------

    /**
    * XOR key + string Combiner
    *
    * Takes a string and key as input and computes the difference using XOR
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   string
    */
    private function _xor_merge($string, $key)
    {
        $hash = $this->hash_encode($key);
        $str = '';
        for ($i = 0; $i < strlen($string); $i++)
        {
            $str .= substr($string, $i, 1) ^ substr($hash, ($i % strlen($hash)), 1);
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
    * Encrypt using Mcrypt
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   string
    */
    private function mcrypt_encode($data, $key)
    {
        $init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());
        $init_vect = mcrypt_create_iv($init_size, MCRYPT_RAND);
        return $this->_add_cipher_noise($init_vect.mcrypt_encrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), $key);
    }

    // --------------------------------------------------------------------

    /**
    * Decrypt using Mcrypt
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   string
    */
    private function mcrypt_decode($data, $key)
    {
        $data = $this->_remove_cipher_noise($data, $key);
        $init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());

        if ($init_size > strlen($data))
        {
            return FALSE;
        }

        $init_vect = substr($data, 0, $init_size);
        $data = substr($data, $init_size);
        return rtrim(mcrypt_decrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), "\0");
    }

    // --------------------------------------------------------------------

    /**
    * Adds permuted noise to the IV + encrypted data to protect
    * against Man-in-the-middle attacks on CBC mode ciphers
    * http://www.ciphersbyritter.com/GLOSSARY.HTM#IV
    *
    * Function description
    *
    * @access   private
    * @param    string
    * @param    string
    * @return   string
    */
    private function _add_cipher_noise($data, $key)
    {
        $keyhash = $this->hash_encode($key);
        $keylen = strlen($keyhash);
        $str = '';

        for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j)
        {
            if ($j >= $keylen)
            {
                $j = 0;
            }

            $str .= chr((ord($data[$i]) + ord($keyhash[$j])) % 256);
        }

        return $str;
    }

    // --------------------------------------------------------------------

    /**
    * Removes permuted noise from the IV + encrypted data, reversing
    * _add_cipher_noise()
    *
    * Function description
    *
    * @access   private
    * @param    type
    * @return   type
    */
    private function _remove_cipher_noise($data, $key)
    {
        $keyhash = $this->hash_encode($key);
        $keylen = strlen($keyhash);
        $str = '';

        for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j)
        {
            if ($j >= $keylen)
            {
                $j = 0;
            }

            $temp = ord($data[$i]) - ord($keyhash[$j]);

            if ($temp < 0)
            {
                $temp = $temp + 256;
            }
            
            $str .= chr($temp);
        }

        return $str;
    }

    // --------------------------------------------------------------------
    
    /**
    * Set the Mcrypt Cipher
    *
    * @access   public
    * @param    constant
    * @return   string
    */
    public function set_cipher($cipher)
    {
        $this->_mcrypt_cipher = $cipher;
    }

    // --------------------------------------------------------------------

    /**
    * Set the Mcrypt Mode
    *
    * @access   public
    * @param    constant
    * @return   string
    */
    public function set_mode($mode)
    {
        $this->_mcrypt_mode = $mode;
    }

    // --------------------------------------------------------------------

    /**
    * Get Mcrypt cipher Value
    *
    * @access    private
    * @return    string
    */
    private function _get_cipher()
    {
        if ($this->_mcrypt_cipher == '')
        {
            $this->_mcrypt_cipher = MCRYPT_RIJNDAEL_256;
        }

        return $this->_mcrypt_cipher;
    }

    // --------------------------------------------------------------------

    /**
    * Get Mcrypt Mode Value
    *
    * @access    private
    * @return    string
    */
    private function _get_mode()
    {
        if ($this->_mcrypt_mode == '')
        {
            $this->_mcrypt_mode = MCRYPT_MODE_ECB;
        }
        
        return $this->_mcrypt_mode;
    }

    // --------------------------------------------------------------------

    /**
    * Set the Hash type
    *
    * @access   public
    * @param    string
    * @return   string
    */
    public function set_hash($type = 'sha1')
    {
        $this->_hash_type = ($type != 'sha1' AND $type != 'md5') ? 'sha1' : $type;
    }

    // --------------------------------------------------------------------

    /**
    * Hash encode a string
    *
    * @access   private
    * @param    string
    * @return   string
    */    
    private function hash_encode($str)
    {
        return ($this->_hash_type == 'sha1') ? sha1($str) : md5($str);
    }

    // --------------------------------------------------------------------

    /**
    * Generate an SHA1 Hash
    *
    * @access   public
    * @param    string
    * @return   string
    */
    public function sha1($str)
    {
        return sha1($str);
    }
    
}

// END Encrypt class

/* End of file Encrypt.php */
/* Location: ./obullo/libraries/Encrypt.php */