<?php
defined('BASE') or exit('Access Denied!');

/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 * 
 * @package         obullo       
 * @author          obullo.com
 * @copyright       Obullo Team
 * @filesource
 * @license
 */
 
// ------------------------------------------------------------------------

/**
 * Ftp Class
 *
 * @package       Obullo
 * @subpackage    Base
 * @category      Libraries
 * @author        Obullo Team
 * @link        
 */
Class OB_Ftp {

    public $hostname    = '';
    public $username    = '';
    public $password    = '';
    public $port        = 21;
    public $passive     = TRUE;
    public $debug       = FALSE;
    public $conn_id     = FALSE;
    
    /**
    * Constructor - Sets Preferences
    *
    * The constructor can be passed an array of config values
    */
    function __construct($config = array())
    {
        if (count($config) > 0)
        {
            $this->init($config);
        }

        log_me('debug', "FTP Class Initialized");
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

        // Prep the hostname
        $this->hostname = preg_replace('|.+?://|', '', $this->hostname);
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

        if (FALSE === ($this->conn_id = @ftp_connect($this->hostname, $this->port)))
        {
            if ($this->debug == TRUE)
            {
                $this->_error('ftp_unable_to_connect');
            }
            return FALSE;
        }

        if ( ! $this->_login())
        {
            if ($this->debug == TRUE)
            {
                $this->_error('ftp_unable_to_login');
            }
            return FALSE;
        }

        // Set passive mode if needed
        if ($this->passive == TRUE)
        {
            ftp_pasv($this->conn_id, TRUE);
        }

        return TRUE;
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
    private function _is_conn()
    {
        if ( ! is_resource($this->conn_id))
        {
            if ($this->debug == TRUE)
            {
                $this->_error('ftp_no_connection');
            }
            return FALSE;
        }
        return TRUE;
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
    public function changedir($path = '', $supress_debug = FALSE)
    {
        if ($path == '' OR ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_chdir($this->conn_id, $path);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE AND $supress_debug == FALSE)
            {
                $this->_error('ftp_unable_to_changedir');
            }
            return FALSE;
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * Create a directory
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function mkdir($path = '', $permissions = NULL)
    {
        if ($path == '' OR ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_mkdir($this->conn_id, $path);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('ftp_unable_to_makdir');
            }
            return FALSE;
        }

        // Set file permissions if needed
        if ( ! is_null($permissions))
        {
            $this->chmod($path, (int)$permissions);
        }

        return TRUE;
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
    public function upload($locpath, $rempath, $mode = 'auto', $permissions = NULL)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        if ( ! file_exists($locpath))
        {
            $this->_error('ftp_no_source_file');
            return FALSE;
        }

        // Set the mode if not specified
        if ($mode == 'auto')
        {
            // Get the file extension so we can set the upload type
            $ext = $this->_getext($locpath);
            $mode = $this->_settype($ext);
        }

        $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;

        $result = @ftp_put($this->conn_id, $rempath, $locpath, $mode);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('ftp_unable_to_upload');
            }
            return FALSE;
        }

        // Set file permissions if needed
        if ( ! is_null($permissions))
        {
            $this->chmod($rempath, (int)$permissions);
        }

        return TRUE;
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
    public function rename($old_file, $new_file, $move = FALSE)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_rename($this->conn_id, $old_file, $new_file);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $msg = ($move == FALSE) ? 'ftp_unable_to_rename' : 'ftp_unable_to_move';

                $this->_error($msg);
            }
            return FALSE;
        }

        return TRUE;
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
        return $this->rename($old_file, $new_file, TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * Rename (or move) a file
     *
     * @access    public
     * @param    string
     * @return    bool
     */
    public function delete_file($filepath)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_delete($this->conn_id, $filepath);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('ftp_unable_to_delete');
            }
            return FALSE;
        }

        return TRUE;
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
    public function delete_dir($filepath)
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        // Add a trailing slash to the file path if needed
        $filepath = preg_replace("/(.+?)\/*$/", "\\1/",  $filepath);

        $list = $this->list_files($filepath);

        if ($list !== FALSE AND count($list) > 0)
        {
            foreach ($list as $item)
            {
                // If we can't delete the item it's probaly a folder so
                // we'll recursively call delete_dir()
                if ( ! @ftp_delete($this->conn_id, $item))
                {
                    $this->delete_dir($item);
                }
            }
        }

        $result = @ftp_rmdir($this->conn_id, $filepath);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('ftp_unable_to_delete');
            }
            return FALSE;
        }

        return TRUE;
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
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        $result = @ftp_chmod($this->conn_id, $perm, $path);

        if ($result === FALSE)
        {
            if ($this->debug == TRUE)
            {
                $this->_error('ftp_unable_to_chmod');
            }
            return FALSE;
        }

        return TRUE;
    }

    // --------------------------------------------------------------------

    /**
     * FTP List files in the specified directory
     *
     * @access    public
     * @return    array
     */
    public function list_files($path = '.')
    {
        if ( ! $this->_is_conn())
        {
            return FALSE;
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
        if ( ! $this->_is_conn())
        {
            return FALSE;
        }

        // Open the local file path
        if ($fp = @opendir($locpath))
        {
            // Attempt to open the remote file path.
            if ( ! $this->changedir($rempath, TRUE))
            {
                // If it doesn't exist we'll attempt to create the direcotory
                if ( ! $this->mkdir($rempath) OR ! $this->changedir($rempath))
                {
                    return FALSE;
                }
            }

            // Recursively read the local directory
            while (FALSE !== ($file = readdir($fp)))
            {
                if (@is_dir($locpath.$file) && substr($file, 0, 1) != '.')
                {
                    $this->mirror($locpath.$file."/", $rempath.$file."/");
                }
                elseif (substr($file, 0, 1) != ".")
                {
                    // Get the file extension so we can se the upload type
                    $ext = $this->_getext($file);
                    $mode = $this->_settype($ext);

                    $this->upload($locpath.$file, $rempath.$file, $mode);
                }
            }
            return TRUE;
        }

        return FALSE;
    }


    // --------------------------------------------------------------------

    /**
     * Extract the file extension
     *
     * @access    private
     * @param    string
     * @return    string
     */
    private function _getext($filename)
    {
        if (FALSE === strpos($filename, '.'))
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
    private function _settype($ext)
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
        if ( ! $this->_is_conn())
        {
            return FALSE;
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
        loader::lang('ob/ftp');
        
        show_error(lang($line));
    }


}
// END FTP Class

/* End of file Ftp.php */
/* Location: ./obullo/libraries/Ftp.php */