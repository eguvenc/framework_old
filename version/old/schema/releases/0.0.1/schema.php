<?php

/**
 * Schema Class
 *
 * @package       packages 
 * @subpackage    schema
 * @category      models
 * @link            
 */

Class Schema {

	public $tablename;    // Schema tablename
	public $driver;		  // Schema driver object
    public $debug;        // Debug on / off
    public $debugOutput;  // Debug output string
    public $config;       // Schema config
    public $output;       // Set schema content
    public $requestUri;

	/**
	 * Constructor
	 * 
	 * @param string $tablename
	 */
	public function __construct($tablename, $dbObject = null, $requestUri)
	{
        global $logger;

        $this->tablename   = strtolower($tablename);
        $this->dbObject    = $dbObject;
        $this->debug       = false;  // debug for developers
        $this->debugOutput = '';
        $this->config      = getConfig('schema');
        $this->requestUri  = $requestUri;

		$schemaDriver = $this->getDriverName();

        if( ! packageExists(strtolower($schemaDriver)))  // Check schema driver is exists
        {
            throw new Exception('Schema driver '.$schemaDriver.' package not installed');
        }

		$this->driver = new $schemaDriver($this);  // Call valid schema driver

        $logger->debug('Schema Class Initialized');
	}

    // --------------------------------------------------------------------

	/**
	 * Read column schema from database
	 * 
	 * @return string schema string array data
	 */
	public function read()
	{
		return $this->driver->read();
	}

    // --------------------------------------------------------------------

    /**
     * Check table is exists
     * 
     * @return boolean
     */
    public function tableExists()
    {
	    if($this->driver->tableExists())
	    {
	        return true;
	    }

        return false;
    }

    // --------------------------------------------------------------------

    /**
     * Create table
     * 
     * @return void
     */
    public function createTable()
    {
        $sql = $this->driver->create();    
        
        $this->runQuery();
        $this->displaySqlQueryHtml($sql); // display sql query form

        exit; // stop the current process
    }

    // --------------------------------------------------------------------

    /**
     * Check post action & run sql query
     * 
     * @return void
     */
    public function runQuery()
    {
        if(isset($_POST['query']))
        {
            $this->dbObject->query($_POST['query']);
            $this->redirect();
        }
    }
    
    // ---------------------------------------------------------

    /**
     * Run schema sync tool
     * 
     * @return void
     */
    public static function runSync($tablename)
    {
        global $logger;

        // ** Auto sync just enabled in "debug" mode.
        // ---------------------------------------------------------
        
        $requestUri = base64_encode(getInstance()->uri->getRequestUri());
        $postData   = 'false';

        if(sizeof($_POST) > 0) // Don't send empty post data
        {
            $postData = base64_encode(serialize($_POST));
        }

        $task      = new Task;    // Get tablename from "sync form" if we have sync request otherwise use current tablename.
        $tablename = isset($_POST['lastCurrentSchema']) ? $_POST['lastCurrentSchema'] : $tablename ;
        $output    = $task->run('sync/index/'.$tablename.'/'.$requestUri.'/'.$postData, true);

        // print_r($_POST); exit; // debug On / Off

        if( ! empty($output))
        {
            echo $output;
            exit;
        }
        
        if(isset($_POST['lastCurrentPage']))  // Do redirect while post array is empty, in cli mode we need do redirect to current page.
        {
            $url = new Url;
            $url->redirect(urldecode($_POST['lastCurrentPage']));
        }

        $logger->info('Auto sync enabled on your config file you need to close it in production mode');
    }

    // --------------------------------------------------------------------
    
    public function syncTable()
    {
        $this->driver->sync();
    }

    // --------------------------------------------------------------------

    /**
     * Show sql query to developer
     * 
     * @param  string $sql
     * @return string
     */
    public function displaySqlQueryForm($sql, $queryWarning = '',$disabled = false)
    {
        $form = new Form;

        $html = '<h1>Run sql query for <i><u>'.strtolower($this->getTableName()).'</u></i> table</h1>';
        $html.= $form->open('/'.$this->getRequestUri(), array('method' => 'POST', 'name' => 'query_form', 'id' => 'query_form'));

            $html.= '<div id="query"><pre><textarea id="query" name="query" rows="8" cols="90">'.$sql.'</textarea></pre></div>';
            
            $value = (empty($queryWarning)) ? 'Are you sure of this action ?' : $queryWarning; 

            $html.= '<input type="hidden" id="sure" name="sure" value="'.base64_encode($value).'">';
            $html.= '<input type="hidden" id="confirm" name="confirm" value="no">';
            $html.= '<input type="hidden" id="lastCurrentPage" name="lastCurrentPage" value="'.urlencode($this->getRequestUri()).'">';

            $disabledText = ($disabled) ? ' disabled="disabled" ' : '';

            $html.= '<input type="button" onclick="runQuery();" value="Run Query" '.$disabledText.' />';
            
        $html.= $form->close();

        return $html;
    }

    // --------------------------------------------------------------------
    
    /**
     * Display Html Output
     * 
     * @return string
     */
    public function displaySqlQueryHtml($sql)
    {
        $html = '<html>';
        $html.= '<head>'.$this->writeCss().'</head>';
        $html.= '<body>';

        $html.= $this->displaySqlQueryForm($sql);
        $html.= $this->writeScript();

        $html.= '<p></p>';
        $html.= '<p class="footer" style="font-size:11px;">* You see this screen because of <kbd>auto sync</kbd> feature enabled in <kbd>development</kbd> mode, you can configure it from your config file. Don\'t forget to close it in <kbd>production</kbd> mode.</p>';
        $html.= "\n</body>";
        $html.= "\n</html>";

        echo $html;
    }

    // --------------------------------------------------------------------

    /**
     * Write schema content to file
     * 
     * @param  string $fileContent schema file content
     * @return void
     */
    public function writeToFile($fileContent)
    {
        global $logger;

        if(file_exists($this->getPath()))
        {
            $currentSchema = getSchema($this->tablename); // Get current schema
        }

        // We need this for first time schema creation

        if($fileContent != false AND ! empty($fileContent)) // Write schema content.
        {
            if( ! is_writable(APP .'schemas'. DS))
            {
                throw new Exception("app/schemas/ path is not writable. Please give write permission to this folder.
                <pre>+ app\n+ <b>schemas</b>\n\t*.php\n+ public</pre>");
            }

            if(file_exists($this->getPath())) // remove current file if it exists.
            {
                unlink($this->getPath());
                clearstatcache();
            }
            
            $content = str_replace(
                array('{schemaName}','{filename}','{content}'),
                array('$'.$this->getTableName(), $this->getTableName(). EXT, $fileContent),
                file_get_contents(APP .'templates'. DS .'newschema.tpl')
            );
           
            $content = "<?php \n".$content;

            if ($fp = fopen($this->getPath(), 'ab')) // Create New Schema If Not Exists.
            {
                flock($fp, LOCK_EX);    
                fwrite($fp, $content);
                flock($fp, LOCK_UN);
                fclose($fp);

                chmod($this->getPath(), 0777);

                $logger->debug('New Schema '.$this->getTableName().' Created');
            }

            $this->redirect(); // redirect to user current page
        }
    }
    
    // --------------------------------------------------------------------

    /**
     * Get path of the schema
     * 
     * @return string
     */
    public function getPath()
    {
		return APP .'schemas'. DS .$this->tablename. EXT;
    }

    // --------------------------------------------------------------------

    /**
     * Get valid tablename
     * 
     * @return string
     */
    public function getTableName()
    {
    	return $this->tablename;
    }

    // --------------------------------------------------------------------

    /**
     * Get valid database object
     * 
     * @return object
     */
    public function getDbObject()
    {
    	return $this->dbObject;
    }

    // --------------------------------------------------------------------

    /**
     * Get the schema driver name
     * 
     * @return string
     */
    public function getDriverName()
    {
        $dbConfig = getConfig('database');

        $exp = explode('_', get_class($dbConfig[Db::$var]));
        return 'Schema_'.ucfirst($exp[1]);
    }

    // --------------------------------------------------------------------

    /**
     * Redirect to current page
     * 
     * @return void
     */
    public function redirect()
    {
        $url = new Url;

        if($this->debug == false)
        {
            if(isset($_POST['lastCurrentPage']))
            {
                $url->redirect(urldecode($_POST['lastCurrentPage'])); // Get encoded redirect url from hidden input
            } 
            else 
            {
                $url->redirect(getInstance()->uri->getRequestUri());
            }
        }
    }

    // --------------------------------------------------------------------

    /**
     * Write javascript
     * 
     * @return string
     */
    public function writeScript()
    {
        return "<script type=\"text/javascript\" />
        function removeKey(columnKey, columnType, method){
            var command;
            columnType = decode64(columnType);
            
            command = columnKey + '|' + columnType + '|' + method;
            <!-- alert(command); -->
            document.getElementById('lastSyncCommand').value = command;
            document.getElementById('lastSyncFunc').value = 'removeKey';
            document.forms['sync_table'].submit();
        }
        function addKey(columnKey, columnType, method){
            var command;
            columnType = decode64(columnType);

            command = columnKey + '|' + columnType + '|' + method;
            <!-- alert(command); -->
            document.getElementById('lastSyncCommand').value = command;
            document.getElementById('lastSyncFunc').value = 'addKey';
            document.forms['sync_table'].submit();
        }
        function removeType(columnKey, columnType, method, isNew){
            var command;
            columnType = decode64(columnType);

            if(typeof isNew == \"undefined\"){
                command = columnKey + '|' + columnType + '|' + method;
            } else {
                command = columnKey + '|' + columnType + '|' + method + '|' + isNew;
            }
            document.getElementById('lastSyncCommand').value = command;
            document.getElementById('lastSyncFunc').value = 'removeType';
            document.forms['sync_table'].submit();
            return false;
        }
        function addType(columnKey, columnType, method, isNew){
            var command;
            columnType = decode64(columnType);

            if(typeof isNew == \"undefined\"){
                command = columnKey + '|' + columnType + '|' + method;
            } else {
                command = columnKey + '|' + columnType + '|' + method + '|' + isNew;
            }
            document.getElementById('lastSyncCommand').value = command;
            document.getElementById('lastSyncFunc').value = 'addType';
            document.forms['sync_table'].submit();
            return false;
        }
        function runQuery(){
            var conf = confirm(decode64(document.getElementById('sure').value));
            if (conf == true) {
                var query = document.getElementById('query').innerHTML;
                document.forms['query_form'].submit();
            }   
        }
        function decode64(input) {
             var keyStr = 'ABCDEFGHIJKLMNOP' +
               'QRSTUVWXYZabcdef' +
               'ghijklmnopqrstuv' +
               'wxyz0123456789+/' +
               '=';

             var output = '';
             var chr1, chr2, chr3 = '';
             var enc1, enc2, enc3, enc4 = '';
             var i = 0;

             // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
             var base64test = /[^A-Za-z0-9\+\/\=]/g;
             if (base64test.exec(input)) {
                alert('There were invalid base64 characters in the input text, valid base64 characters are A-Z, a-z, 0-9, +, /,and = expect errors in decoding.');
                return false;
             }
             input = input.replace(/[^A-Za-z0-9\+\/\=]/g, '');

             do {
                enc1 = keyStr.indexOf(input.charAt(i++));
                enc2 = keyStr.indexOf(input.charAt(i++));
                enc3 = keyStr.indexOf(input.charAt(i++));
                enc4 = keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64) {
                   output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                   output = output + String.fromCharCode(chr3);
                }

                chr1 = chr2 = chr3 = '';
                enc1 = enc2 = enc3 = enc4 = '';

             } while (i < input.length);

             return unescape(output);
          }

        </script>";
    }

    // --------------------------------------------------------------------
    
    /**
     * Write css output
     * 
     * @return string
     */
    public function writeCss()
    {  
        global $packages;

        $css_file = PACKAGES .'schema_sync'. DS .'releases'. DS .$packages['dependencies']['schema_sync']['version']. DS .'src'. DS .'schema_sync.css';

        $css = '<style type="text/css">';
        $css.= file_get_contents($css_file);
        $css.= "</style>";

        return $css;
    }

    // --------------------------------------------------------------------

    /**
     * Set output for debugging
     * 
     * @param string $str debug
     */
    public function setDebugOutput($str)
    {
        $this->debugOutput.= $str.'<br />';
    }

    // --------------------------------------------------------------------

    /**
     * Return to debug string
     * 
     * @return string
     */
    public function getDebugOutput()
    {
        if(empty($this->debugOutput))
        {
            return;
        }

        return '<div class="debug"><h1>Debug Output</h1><pre>'.$this->debugOutput.'</pre></div>';
    }

    // --------------------------------------------------------------------

    /**
     * Get current uri
     * 
     * @return string
     */
    public function getRequestUri()
    {
        global $config;
        
        $index_page = $config['index_page'];

        if(empty($index_page))
        {
            return $this->requestUri;
        } 
        else 
        {
            return substr($this->requestUri, strlen($index_page) + 1);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Set output debugging string
     * 
     * @param string $ruleString
     */
    public function setOutput($ruleString)
    {
        $this->output = $ruleString;
    }

    // --------------------------------------------------------------------

    /**
     * Get html output
     * 
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    // --------------------------------------------------------------------

    /**
     * Debug On / Off to fix easily 
     * sync development issues
     * 
     * @return 
     */
    public function debug()
    {
        $this->debug = true;
    }

    // --------------------------------------------------------------------

    /**
     * Create column label automatically
     * 
     * @param  string $field field name
     * @return string column label
     */
    public function _createLabel($field)
    {
        $exp = explode('_', $field); // explode underscores ..

        if($exp)
        {
            $label = '';
            foreach($exp as $val)
            {
                $label.= ucfirst($val).' ';
            }
        } 
        else 
        {
            $label = ucfirst($field);
        }

        return trim($label);
    }
    
}

// END Schema class

/* End of file schema.php */
/* Location: ./packages/schema/releases/0.0.1/schema.php */