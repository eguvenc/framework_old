<?php

/**
 * Ftp Class
 *
 * @package       packages
 * @subpackage    ftp
 * @category      ftp
 * @link        
 */

Class Ftp {

    public $hostname    = '';
    public $username    = '';
    public $password    = '';
    public $port        = 21;
    public $passive     = true;
    public $debug       = false;
    public $conn_id     = false;
    
    /**
    * Constructor - Sets Preferences
    *
    * The constructor can be passed an array of config values
    */
    public function __construct($config = array())
    {   
        global $logger;

        if( ! isset(getInstance()->ftp))
        {
            getInstance()->ftp = $this; // Make available it in the controller $this->ftp->method();
        }
        
        if (count($config) > 0)
        {
            $this->init($config);
        }

        $logger->debug('FTP Class Initialized');
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Initialize preferences
     *
     * @access    public
     * @param    array
     * @return    void
     */
    public function init($config = array())
    {
        foreach ($config as $key => $val)
        {
            if (isset($this->$key))
            {
                $this->$key = $val;
            }
        }

        $this->hostname = preg_replace('|.+?://|', '', $this->hostname); // Prep the hostname
        
        return ($this);
    }
    
    // --------------------------------------------------------------------

    /**
     * FTP Connect
     *
     * @access    public
     * @param    array     the connection values
     * @return    bool
     */
    public function connect($config = array())
    {
        if (count($config) > 0)
        {
            $this->init($config);
        }

        if (false === ($this->conn_id = @ftp_connect($this->hostname, $this->port)))
        {
            if ($this->debug == true)
            {
                $this->_error('ftp_unable_to_connect');
            }
            return false;
        }

        if ( ! $this->_login())
        {
            if ($this->debug == true)
            {
                $this->_error('ftp_unable_to_login');
            }
            return false;
        }

        if ($this->passive == true)  // Set passive mode if needed
        {
            ftp_pasv($this->conn_id, true);
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * FTP Login
     *
     * @access    private
     * @return    bool
     */
    private function _login()
    {
        return @ftp_login($this->conn_id, $this->username, $this->password);
    }

    // --------------------------------------------------------------------

    /**
     * Validates the connection ID
     *
     * @access    private
     * @return    bool
     */
    private function _isConn()
    {
        if ( ! is_resource($this->conn_id))
        {
            if ($this->debug == true)
            {
                $this->_error('ftp_no_connection');
            }
            
            return false;
        }
        
        return true;
    }

    // --------------------------------------------------------------------


    /**
     * Change directory
     *
     * The second parameter lets us momentarily turn off debugging so that
     * this function can be used to test for the existence of a folder
     * without throwing an error.  There's no FTP equivalent to is_dir()
     * so we do it by trying to change to a particular directory.
     * Internally, this parameter is only used by the "mirror" function below.
     *
     * @access    public
     * @param    string
     * @param    bool
     * @return    bool
     */
    public function changeDir($path = '', $supress_debug = false)
    {
        if ($path == '' OR ! $this->_isConn())
        {
            return false;
        }

        $result = @ftp_chdir($this->conn_id, $path);

        if ($result === false)
        {
            if ($this->debug == true AND $supress_debug == false)
            {
                $this->_error('ftp_unable_to_changedir');
            }
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Create a directory
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function mkDir($path = '', $permissions = null)
    {
        if ($path == '' OR ! $this->_isConn())
        {
            return false;
        }

        $result = @ftp_mkdir($this->conn_id, $path);

        if ($result === false)
        {
            if ($this->debug == true)
            {
                $this->_error('ftp_unable_to_makdir');
            }
            return false;
        }

        // Set file permissions if needed
        if ( ! is_null($permissions))
        {
            $this->chmod($path, (int)$permissions);
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Upload a file to the server
     *
     * @access    public
     * @param    string
     * @param    string
     * @param    string
     * @return    bool
     */
    public function upload($locpath, $rempath, $mode = 'auto', $permissions = null)
    {
        if ( ! $this->_isConn())
        {
            return false;
        }

        if ( ! file_exists($locpath))
        {
            $this->_error('ftp_no_source_file');
            return false;
        }

        // Set the mode if not specified
        if ($mode == 'auto')
        {
            // Get the file extension so we can set the upload type
            $ext  = $this->_geText($locpath);
            $mode = $this->_setType($ext);
        }

        $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;

        $result = @ftp_put($this->conn_id, $rempath, $locpath, $mode);

        if ($result === false)
        {
            if ($this->debug == true)
            {
                $this->_error('ftp_unable_to_upload');
            }
            return false;
        }

        // Set file permissions if needed
        if ( ! is_null($permissions))
        {
            $this->chmod($rempath, (int)$permissions);
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Rename (or move) a file
     *
     * @access    public
     * @param    string
     * @param    string
     * @param    bool
     * @return    bool
     */
    public function rename($old_file, $new_file, $move = false)
    {
        if ( ! $this->_isConn())
        {
            return false;
        }

        $result = @ftp_rename($this->conn_id, $old_file, $new_file);

        if ($result === false)
        {
            if ($this->debug == true)
            {
                $msg = ($move == false) ? 'ftp_unable_to_rename' : 'ftp_unable_to_move';

                $this->_error($msg);
            }
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Move a file
     *
     * @access    public
     * @param    string
     * @param    string
     * @return    bool
     */
    public function move($old_file, $new_file)
    {
        return $this->rename($old_file, $new_file, true);
    }

    // --------------------------------------------------------------------

    /**
     * Rename (or move) a file
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function deleteFile($filepath)
    {
        if ( ! $this->_isConn())
        {
            return false;
        }

        $result = @ftp_delete($this->conn_id, $filepath);

        if ($result === false)
        {
            if ($this->debug == true)
            {
                $this->_error('ftp_unable_to_delete');
            }
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Delete a folder and recursively delete everything (including sub-folders)
     * containted within it.
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function deleteDir($filepath)
    {
        if ( ! $this->_isConn())
        {
            return false;
        }

        // Add a trailing slash to the file path if needed
        $filepath = preg_replace("/(.+?)\/*$/", "\\1/",  $filepath);

        $list = $this->listFiles($filepath);

        if ($list !== false AND count($list) > 0)
        {
            foreach ($list as $item)
            {
                // If we can't delete the item it's probaly a folder so
                // we'll recursively call deleteDir()
                if ( ! @ftp_delete($this->conn_id, $item))
                {
                    $this->deleteDir($item);
                }
            }
        }

        $result = @ftp_rmdir($this->conn_id, $filepath);

        if ($result === false)
        {
            if ($this->debug == true)
            {
                $this->_error('ftp_unable_to_delete');
            }
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Set file permissions
     *
     * @access    public
     * @param    string     the file path
     * @param    string    the permissions
     * @return    bool
     */
    public function chmod($path, $perm)
    {
        if ( ! $this->_isConn())
        {
            return false;
        }

        $result = @ftp_chmod($this->conn_id, $perm, $path);

        if ($result === false)
        {
            if ($this->debug == true)
            {
                $this->_error('ftp_unable_to_chmod');
            }
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * FTP List files in the specified directory
     *
     * @access    public
     * @return    array
     */
    public function listFiles($path = '.')
    {
        if ( ! $this->_isConn())
        {
            return false;
        }

        return ftp_nlist($this->conn_id, $path);
    }

    // ------------------------------------------------------------------------

    /**
     * Read a directory and recreate it remotely
     *
     * This function recursively reads a folder and everything it contains (including
     * sub-folders) and creates a mirror via FTP based on it.  Whatever the directory structure
     * of the original file path will be recreated on the server.
     *
     * @access    public
     * @param    string    path to source with trailing slash
     * @param    string    path to destination - include the base folder with trailing slash
     * @return    bool
     */
    public function mirror($locpath, $rempath)
    {
        if ( ! $this->_isConn())
        {
            return false;
        }

        // Open the local file path
        if ($fp = @opendir($locpath))
        {
            // Attempt to open the remote file path.
            if ( ! $this->changeDir($rempath, true))
            {
                // If it doesn't exist we'll attempt to create the direcotory
                if ( ! $this->mkDir($rempath) OR ! $this->changeDir($rempath))
                {
                    return false;
                }
            }

            // Recursively read the local directory
            while (false !== ($file = readdir($fp)))
            {
                if (@is_dir($locpath.$file) && substr($file, 0, 1) != '.')
                {
                    $this->mirror($locpath.$file."/", $rempath.$file."/");
                }
                elseif (substr($file, 0, 1) != ".")
                {
                    // Get the file extension so we can se the upload type
                    $ext  = $this->_geText($file);
                    $mode = $this->_setType($ext);

                    $this->upload($locpath.$file, $rempath.$file, $mode);
                }
            }
            return true;
        }

        return false;
    }


    // --------------------------------------------------------------------

    /**
     * Extract the file extension
     *
     * @access    private
     * @param    string
     * @return    string
     */
    private function _geText($filename)
    {
        if (false === strpos($filename, '.'))
        {
            return 'txt';
        }

        $x = explode('.', $filename);
        return end($x);
    }


    // --------------------------------------------------------------------

    /**
     * Set the upload type
     *
     * @access    private
     * @param    string
     * @return    string
     */
    private function _setType($ext)
    {
        $text_types = array(
                            'txt',
                            'text',
                            'php',
                            'phps',
                            'php4',
                            'php5',
                            'js',
                            'css',
                            'htm',
                            'html',
                            'phtml',
                            'shtml',
                            'log',
                            'xml'
                            );


        return (in_array($ext, $text_types)) ? 'ascii' : 'binary';
    }

    // ------------------------------------------------------------------------

    /**
     * Close the connection
     *
     * @access    public
     * @param    string    path to source
     * @param    string    path to destination
     * @return    bool
     */
    public function close()
    {
        if ( ! $this->_isConn())
        {
            return false;
        }

        @ftp_close($this->conn_id);
    }

    // ------------------------------------------------------------------------

    /**
     * Display error message
     *
     * @access    private
     * @param    string
     * @return    bool
     */
    private function _error($line)
    {
        getInstance()->translator->load('ftp'); // load framework language file.
        getInstance()->response->showError(translate($line));
    }

}
// END Ftp Class

/* End of file Ftp.php */
/* Location: ./packages/ftp/releases/0.0.1/ftp.php */