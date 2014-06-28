<?php

/**
 * Zip Compression Class
 *
 * This class is based on a library I found at Zend:
 * http://www.zend.com/codex.php?id=696&single=1
 *
 * Codes borrowed from Codeigniter Framework
 *
 * @package       packages
 * @subpackage    zip
 * @category      zip
 * @link           
 */

Class Zip {

    public $zipdata     = '';
    public $directory   = '';
    public $entries     = 0;
    public $file_num    = 0;
    public $offset      = 0;
    public $now;
    
    public function __construct()
    {
        global $logger;

        if( ! isset(getInstance()->zip))
        {
            getInstance()->zip = $this; // Make available it in the controller $this->zip->method();
        }

        $logger->debug('Zip Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Add Directory
     *
     * Lets you add a virtual directory into which you can place files.
     *
     * @access    public
     * @param    mixed    the directory name. Can be string or array
     * @return    void
     */
    public function addDir($directory)
    {
        foreach ((array)$directory as $dir)
        {
            if ( ! preg_match("|.+/$|", $dir))
            {
                $dir .= '/';
            }

            $dir_time = $this->_getModTime($dir);

            $this->_addDir($dir, $dir_time['file_mtime'], $dir_time['file_mdate']);
        }
    }

    // --------------------------------------------------------------------    

    /**
     * Get file/directory modification time
     * If this is a newly created file/dir, we will set the time to 'now'
     *
     * @param string    path to file
     * @return array     filemtime/filemdate
     */
    public function _getModTime($dir)
    {
        // filemtime() will return false, but it does raise an error.
        $date = (@filemtime($dir)) ? filemtime($dir) : getdate($this->now); 

        $time['file_mtime'] = ($date['hours'] << 11) + ($date['minutes'] << 5) + $date['seconds'] / 2;
        $time['file_mdate'] = (($date['year'] - 1980) << 9) + ($date['mon'] << 5) + $date['mday'];
        
        return $time;
    }

    // --------------------------------------------------------------------

    /**
     * Add Directory
     *
     * @access    private
     * @param    string    the directory name
     * @return    void
     */
    public function _addDir($dir, $file_mtime, $file_mdate)
    {        
        $dir = str_replace("\\", "/", $dir);

        $this->zipdata .=
            "\x50\x4b\x03\x04\x0a\x00\x00\x00\x00\x00"
            .pack('v', $file_mtime)
            .pack('v', $file_mdate)
            .pack('V', 0) // crc32
            .pack('V', 0) // compressed filesize
            .pack('V', 0) // uncompressed filesize
            .pack('v', strlen($dir)) // length of pathname
            .pack('v', 0) // extra field length
            .$dir
            // below is "data descriptor" segment
            .pack('V', 0) // crc32
            .pack('V', 0) // compressed filesize
            .pack('V', 0); // uncompressed filesize

        $this->directory .=
            "\x50\x4b\x01\x02\x00\x00\x0a\x00\x00\x00\x00\x00"
            .pack('v', $file_mtime)
            .pack('v', $file_mdate)
            .pack('V',0) // crc32
            .pack('V',0) // compressed filesize
            .pack('V',0) // uncompressed filesize
            .pack('v', strlen($dir)) // length of pathname
            .pack('v', 0) // extra field length
            .pack('v', 0) // file comment length
            .pack('v', 0) // disk number start
            .pack('v', 0) // internal file attributes
            .pack('V', 16) // external file attributes - 'directory' bit set
            .pack('V', $this->offset) // relative offset of local header
            .$dir;

        $this->offset = strlen($this->zipdata);
        $this->entries++;
    }
    
    // --------------------------------------------------------------------

    /**
     * Add Data to Zip
     *
     * Lets you add files to the archive. If the path is included
     * in the filename it will be placed within a directory.  Make
     * sure you use add_dir() first to create the folder.
     *
     * @access    public
     * @param    mixed
     * @param    string
     * @return    void
     */    
    public function addData($filepath, $data = null)
    {    
        if (is_array($filepath))
        {
            foreach ($filepath as $path => $data)
            {
                $file_data = $this->_getModTime($path);    
                $this->_addData($path, $data, $file_data['file_mtime'], $file_data['file_mdate']);
            }
        }
        else
        {
            $file_data = $this->_getModTime($filepath);
            $this->_addData($filepath, $data, $file_data['file_mtime'], $file_data['file_mdate']);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Add Data to Zip
     *
     * @access   private
     * @param    string    the file name/path
     * @param    string    the data to be encoded
     * @return   void
     */    
    private function _addData($filepath, $data, $file_mtime, $file_mdate)
    {
        $filepath = str_replace("\\", "/", $filepath);

        $uncompressed_size = strlen($data);
        $crc32  = crc32($data);

        $gzdata = gzcompress($data);
        $gzdata = substr($gzdata, 2, -4);
        $compressed_size = strlen($gzdata);

        $this->zipdata .=
            "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00"
            .pack('v', $file_mtime)
            .pack('v', $file_mdate)
            .pack('V', $crc32)
            .pack('V', $compressed_size)
            .pack('V', $uncompressed_size)
            .pack('v', strlen($filepath)) // length of filename
            .pack('v', 0) // extra field length
            .$filepath
            .$gzdata; // "file data" segment

        $this->directory .=
            "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00"
            .pack('v', $file_mtime)
            .pack('v', $file_mdate)
            .pack('V', $crc32)
            .pack('V', $compressed_size)
            .pack('V', $uncompressed_size)
            .pack('v', strlen($filepath)) // length of filename
            .pack('v', 0) // extra field length
            .pack('v', 0) // file comment length
            .pack('v', 0) // disk number start
            .pack('v', 0) // internal file attributes
            .pack('V', 32) // external file attributes - 'archive' bit set
            .pack('V', $this->offset) // relative offset of local header
            .$filepath;

        $this->offset = strlen($this->zipdata);
        $this->entries++;
        $this->file_num++;
    }
    
    // --------------------------------------------------------------------

    /**
     * Read the contents of a file and add it to the zip
     *
     * @access    public
     * @return    bool
     */    
    public function readFile($path, $preserve_filepath = false)
    {
        if ( ! file_exists($path))
        {
            return false;
        }

        if (false !== ($data = file_get_contents($path)))
        {
            $name = str_replace("\\", "/", $path);
            
            if ($preserve_filepath === false)
            {
                $name = preg_replace("|.*/(.+)|", "\\1", $name);
            }

            $this->addData($name, $data);
            return true;
        }
        return false;
    }

    // ------------------------------------------------------------------------
    
    /**
     * Read a directory and add it to the zip.
     *
     * This function recursively reads a folder and everything it contains (including
     * sub-folders) and creates a zip based on it.  Whatever directory structure
     * is in the original file path will be recreated in the zip file.
     *
     * @access    public
     * @param    string    path to source
     * @return    bool
     */
    public function readDir($path, $preserve_filepath = true, $root_path = null)
    {
        if ( ! $fp = @opendir($path))
        {
            return false;
        }

        // Set the original directory root for child dir's to use as relative
        if ($root_path === null)
        {
            $root_path = dirname($path).'/';
        }

        while (false !== ($file = readdir($fp)))
        {
            if(substr($file, 0, 1) == '.')
            {
                continue;
            }

            if (@is_dir($path.$file))
            {
                $this->readDir($path.$file."/", $preserve_filepath, $root_path);
            }
            else
            {
                if (false !== ($data = file_get_contents($path.$file)))
                {
                    $name = str_replace("\\", "/", $path);

                    if ($preserve_filepath === false)
                    {
                        $name = str_replace($root_path, '', $name);
                    }

                    $this->addData($name.$file, $data);
                }
            }
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Get the Zip file
     *
     * @access    public
     * @return    binary string
     */    
    public function getZip()
    {
        // Is there any data to return?
        if ($this->entries == 0)
        {
            return false;
        }

        $zip_data = $this->zipdata;
        $zip_data .= $this->directory."\x50\x4b\x05\x06\x00\x00\x00\x00";
        $zip_data .= pack('v', $this->entries); // total # of entries "on this disk"
        $zip_data .= pack('v', $this->entries); // total # of entries overall
        $zip_data .= pack('V', strlen($this->directory)); // size of central dir
        $zip_data .= pack('V', strlen($this->zipdata)); // offset to start of central dir
        $zip_data .= "\x00\x00"; // .zip file comment length

        return $zip_data;
    }
    
    // --------------------------------------------------------------------

    /**
     * Write File to the specified directory
     *
     * Lets you write a file
     *
     * @access   public
     * @param    string    the file name
     * @return   bool
     */    
    public function archive($filepath)
    {
        if ( ! ($fp = @fopen($filepath, 'wb')))
        {
            return false;
        }

        flock($fp, LOCK_EX);    
        fwrite($fp, $this->getZip());
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;    
    }

    // --------------------------------------------------------------------

    /**
     * Download
     *
     * @access   public
     * @param    string    the file name
     * @param    string    the data to be encoded
     * @return   bool
     */
    public function download($filename = 'backup.zip')
    {
        if ( ! preg_match("|.+?\.zip$|", $filename))
        {
            $filename .= '.zip';
        }
        
        $get_zip = $this->getZip();
        $data    =& $get_zip;

           // Try to determine if the filename includes a file extension.
        // We need it in order to set the MIME type
        if (false === strpos($filename, '.'))
        {
            return false;
        }

        // Grab the file extension
        $x = explode('.', $filename);
        $extension = end($x);

        // Load the mime types
        $mimes = getConfig('mimes');  // Obullo changes ..

        // Set a default mime if we can't find it
        if ( ! isset($mimes[$extension]))
        {
            $mime = 'application/octet-stream';
        }
        else
        {
            $mime = (is_array($mimes[$extension])) ? $mimes[$extension][0] : $mimes[$extension];
        }

        // Generate the server headers
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
        {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: ".strlen($data));
        }
        else
        {
            header('Content-Type: "'.$mime.'"');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: ".strlen($data));
        }

        exit($data);
    }

    // --------------------------------------------------------------------

    /**
     * Initialize Data
     *
     * Lets you clear current zip data.  Useful if you need to create
     * multiple zips with different data.
     *
     * @access    public
     * @return    void
     */        
    public function clearData()
    {
        $this->zipdata      = '';
        $this->directory    = '';
        $this->entries      = 0;
        $this->file_num     = 0;
        $this->offset       = 0;
    }
    
}

/* End of file zip.php */
/* Location: ./packages/zip/releases/0.0.1/zip.php */