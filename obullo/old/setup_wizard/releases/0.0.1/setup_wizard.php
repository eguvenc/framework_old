<?php

/**
 * Setup Wizard Class
 *
 * @package       packages
 * @subpackage    setup_wizard
 * @category      
 * @link
 */

Class Setup_Wizard {
    
    private $_loadedExtensions  = array();
    private $_input             = array();
    private $_css_file          = null;
    private $_database_template = '/app/templates/database.tpl';
    private $_dbPath;

    // Database connection variables
    private $_dbName;
    private $_dbDriver;
    private $_dbPrefix;
    private $_dbhPort;

    private $_title;
    private $_sub_title;
    private $_db;

    protected $ini_line = array();
    protected $wizard;  // Model wizard
    protected $form;    // Form object
    protected $post;    // Post object

    /**
     * Constructor
     */
    public function __construct()
    {
        global $logger;

        if( ! isset(getInstance()->setup_wizard))
        {
            getInstance()->setup_wizard = $this; // Available it in the contoller $this->setup_wizard->method();
        }

        $this->form = new Form;
        
        $logger->debug('Setup Wizard Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Set extension
     * 
     * @param string | array $driver
     * @return  void
     */
    public function setExtension($ext)
    {
        if(is_array($ext))
        {
            $this->_loadedExtensions = $ext;
        } 
        else 
        {
            array_push($this->_loadedExtensions, $ext);
        }
    }

    // --------------------------------------------------------------------

    /**
     * Extension control for requirements driver
     * 
     * @return html $extension
     */
    private function _checkExtensions()
    {
        $extension = '';
        foreach($this->_loadedExtensions as $driver)
        {
            if( ! extension_loaded($driver))
            {
                $extension.= '<tr><td class="newColumn">'.$driver.'<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div></td><td id="driverError" class="columnTypeError">Not installed<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div></td></tr>';
            }
            else
            {
                $extension.= '<tr><td class="newColumn">'.$driver.'<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div></td><td class="green">Pass<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div></td></tr>';
            }
        }

        return $extension;
    }

    // --------------------------------------------------------------------

    /**
     * Create SQL
     * 
     * @return boolean true or exception
     */
    private function _createSQL()
    {
        $sql    = $this->post->get('sql');
        $result = $this->_db->exec($sql);

        if($result > 0)
        {
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Get SQL output
     * 
     * @return string
     */
    public function getSQL()
    {
        return $sql = file_get_contents($this->_dbPath);
    }

    // --------------------------------------------------------------------

    /**
     * Write ini
     * 
     * @return string
     */
    public function writeIni()
    {
        return $this->_filePutContents($this->_ini_file, implode('',$this->ini_line));
    }

    // --------------------------------------------------------------------

    /**
     * File rewrite
     * 
     * @param  string $fileName
     * @param  string $data
     * @return write setup_wizard.ini file
     */
    private function _filePutContents($fileName, $data)
    {
        if( ! file_put_contents($fileName, $data, LOCK_EX))
        {
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Create form html
     * 
     * @return html
     */
    public function createForm()
    {
        $form = '';
        
        foreach($this->_input as $input)
        {
            if($input['name'] == 'password')
            {
                $form.= '<tr>';
                $form.= '<td class="newColumn">';
                $form.= $this->form->label($input['label']);
                $form.= '<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div>';
                $form.= '</td>';
                $form.= '<td class="error">';
                $form.= $this->form->error($input['name']).$this->form->password($input['name'], $input['value'], $input['attr']);
                $form.= '<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div>';
                $form.= '</td>';
                $form.= '</tr>';
            }
            else
            {
                $form.= '<tr>';
                $form.= '<td class="newColumn">';
                $form.= $this->form->label($input['label']);
                $form.= '<div class="columnUpdateWrapper"><div class="columnNewRow"></div></div>';
                $form.= '</td>';
                $form.= '<td class="error">';
                $form.= $this->form->error($input['name']).$this->form->input($input['name'],$input['value'], $input['attr']);
                $form.= '<div class="columnUpdateWrapper"><div class="columnNewRow"><div style="clear:left;"></div>';
                $form.= '</td>';
                $form.= '</tr>';
            }
        }

        // foreach(){} textarea
        // foreach(){} dropdown

        $form.= '<tr><td class="newColumn" colspan="2">'.$this->form->textarea('sql',$this->getSQL(),' rows="10" style="width:485px;"').'</tr>';

        return $form;
    }

    // --------------------------------------------------------------------

    /**
     * Run last function
     * 
     * @return function
     */
    public function run()
    {
        $ini_data = $this->getIni();
        
        if(isset($ini_data['installed']) AND $ini_data['installed'] == 1)
        {
            return false;
        }

        $this->post = new Post;

        if($this->post->get('submit_step2'))
        {
            new Model('wizard', false);

            $this->wizard = getInstance()->wizard;

            foreach($this->_input as $rule)
            {
                $this->form->setRules($rule['name'], $rule['label'], $rule['rule']);
            }

            if($this->wizard->isValid())
            {
                if( ! $this->checkDatabaseAccess())
                {
                    $this->wizard->setMessage('message','Wrong username or password');
                }
                elseif( ! $this->checkDatabaseExists())
                {
                    $this->wizard->setMessage('message', $this->_dbName.' database already exists.');
                }
                elseif( ! $this->_createSQL())
                {
                    $this->wizard->setMessage('message','Database not installed');
                }
                elseif( ! $this->writeDatabaseConfig())
                {
                    $this->wizard->setMessage('message','Database config file could not be created.');
                }
                elseif( ! $this->writeIni())
                {
                    $this->wizard->setMessage('message','Setup.ini file could not be written.');
                }
                else
                {
                    $url = new Url;
                    $url->redirect($this->getRedirectUrl());
                }
            }
        }
        
        if(count($this->_loadedExtensions) > 0 AND count($this->_input) == 0)
        {
            $this->createExtensionHtml(); // step1
        }
        elseif(count($this->_input) > 0)
        {
            $this->createFormHtml();      // step2
        }

        exit;
    }

    // --------------------------------------------------------------------

    /**
     * Set database config filename
     * 
     * @return  void
     */
    public function setDatabaseConfigFile($filepath)
    {
        $this->sql_file_path = $filepath;
    }

    // --------------------------------------------------------------------

    /**
     * Get database configuration file
     * 
     * @return string
     */
    public function getDatabaseConfigFile()
    {
        $path = str_replace('\/', DS, trim($this->sql_file_path, '/'));
        $path = str_replace('app', rtrim(APP, DS), $path);

        return $path;
    }

    // --------------------------------------------------------------------

    public function setDatabasePath($file)
    {
        $this->_dbPath = str_replace('\/', DS, $file);

        if( ! file_exists($this->_dbPath))
        {
            throw new Exception($path.' file not found');
        }
    }

    // --------------------------------------------------------------------

    public function getDatabasePath()
    {
        return $this->_dbPath;
    }

    // --------------------------------------------------------------------

    /**
     * Set database and db path
     * 
     * @param  string $database db name
     * @param  string $db_path
     * @return object
     */
    public function setDatabase($database)
    {
        $this->_dbName = $database;

        return $this;
    }

    // --------------------------------------------------------------------

    public function getDatabaseName()
    {
        return $this->_dbName;
    }

    // --------------------------------------------------------------------

    public function setDatabaseDriver($dbDriver = 'Pdo_Mysql')
    {
        $this->_dbDriver = $dbDriver;
    }
    
    // --------------------------------------------------------------------

    public function getDatabaseDriver()
    {
        return $this->_dbDriver;
    }

    // --------------------------------------------------------------------

    public function setDatabaseTemplate($template = null)
    {
        $path = str_replace('\/', DS, trim($template, '/'));
        $path = str_replace('app', rtrim(APP, DS), $path);

        $this->_database_template = (empty($template)) ? APP. 'templates'. DS .'database.tpl' : $path;
    }

    // --------------------------------------------------------------------

    public function getDatabaseTemplate()
    {
        return $this->_database_template;
    }

    // --------------------------------------------------------------------

    /**
     * Set your custom css file
     * 
     * @param string $css
     * @return void
     */
    public function setCssFile($css = '')
    {
        $this->_css_file = $css;
    }

    // --------------------------------------------------------------------

    /**
     * Get css file url
     * 
     * @return string
     */
    public function getCssFile()
    {
        return (empty($this->_css_file)) ? '/assets/css/setup_wizard.css' : $this->_css_file;
    }

    // --------------------------------------------------------------------

    /**
     * Set title
     * 
     * @param string $title
     */
    public function setTitle($title)
    {
        return $this->_title = $title;
    }

    // --------------------------------------------------------------------

    /**
     * Set sub title
     * 
     * @param string $sub_title
     */
    public function setSubTitle($sub_title)
    {
        return $this->_sub_title = $sub_title;
    }

    // --------------------------------------------------------------------

    /**
     * Set input
     * 
     * @param  string $name
     * @param  string $label
     * @return array
     */
    public function setInput($name, $label = '', $rule = '', $value = '', $attr = '')
    {
        $input          = array();
        $input['name']  = $name;
        $input['rule']  = $rule;
        $input['label'] = $label;
        $input['value'] = $value;
        $input['attr']  = $attr;

        array_push($this->_input, $input);
    }

    // --------------------------------------------------------------------
    
    /**
     * Set installation note
     * 
     * @param string $note
     */
    public function setNote($note = '')
    {
        $this->installation_note = $note;
    }

    // --------------------------------------------------------------------

    /**
     * Get installation note
     * 
     * @return string
     */
    public function getNote()
    {
        return $this->installation_note;
    }

    // --------------------------------------------------------------------

    /**
     * Set redirect url then we redirect user
     * to this page end of the wizard.
     * 
     * @param string $url
     */
    public function setRedirectUrl($url = '/')
    {
        $this->redirectUrl = $url;
    }

    // --------------------------------------------------------------------

    /**
     * Get redirect url string
     * 
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    // --------------------------------------------------------------------

    /**
     * Set setup ini file
     * 
     * @return array
     */
    public function setIni($key = 'installed', $value = 1)
    {
        $ini_line = $key.' = '.$value;

        return array_push($this->ini_line, $ini_line);
    }

    // --------------------------------------------------------------------

    /**
     * Get ini content
     * 
     * @return array
     */
    public function getIni()
    {
        return parse_ini_file($this->getIniFile());
    }

    // --------------------------------------------------------------------

    /**
     * Get ini file
     * 
     * @return string ini file
     */
    public function getIniFile()
    {
        return $this->_ini_file = DATA . 'cache' . DS . 'setup_wizard.ini';
    }

    // --------------------------------------------------------------------
    
    /**
     * Create database.php config file
     * in app/config/debug/ folder.
     * 
     * @return bool
     */
    private function writeDatabaseConfig()
    {
        $db_config = $this->getDatabaseConfigFile();
        $data      = $this->getDatabaseTemplateFile();

        return $this->_filePutContents($db_config, $data);
    }
    
    // --------------------------------------------------------------------

    public function setDatabaseItem($item, $value)
    {
        $this->{$item} = $value;
    }

    // --------------------------------------------------------------------

    public function getDatabaseItem($item)
    {
        return $this->{$item};
    }
    
    // --------------------------------------------------------------------

    /**
     * Get db config template file
     * 
     * @return string
     */
    public function getDatabaseTemplateFile()
    {        
        $pattern = array(
            '{database_driver}' => $this->getDatabaseDriver(),
            '{variable}' => 'db',
            '{hostname}' => $this->getDatabaseItem('hostname'),
            '{username}' => $this->getDatabaseItem('username'),
            '{password}' => $this->getDatabaseItem('password'),
            '{database}' => $this->getDatabaseItem('database'),
            '{driver}'   => '',
            '{prefix}'   => $this->getDatabaseItem('prefix'),
            '{dbh_port}' => $this->getDatabaseItem('dbh_port'),
            '{char_set}' => $this->getDatabaseItem('char_set'),
            '{dsn}'      => $this->getDatabaseItem('dsn'),
            '{options}'  => $this->getDatabaseItem('options'),
        );

        $template = file_get_contents($this->getDatabaseTemplate());

        return str_replace(array_keys($pattern), array_values($pattern), $template);
    }

    // --------------------------------------------------------------------

    /**
     * Database Access
     * 
     * @return boolean true or false
     */
    public function checkDatabaseAccess()
    {
        $hostname = $this->getDatabaseItem('hostname');
        $username = $this->getDatabaseItem('username');
        $password = $this->getDatabaseItem('password');

        try
        {
            $this->_db = new PDO("mysql:host=$hostname", $username, $password);
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } 
        catch (PDOException $e)
        {
            preg_match('/Access denied/',$e->getMessage(),$exception);
        }

        if(isset($exception[0]) AND $exception[0] == 'Access denied')
        {
            return false;
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Database exists ?
     * 
     * @return boolean true or false
     */
    private function checkDatabaseExists()
    {
        $dbs = $this->_db->query('SHOW DATABASES');

        while(($db = $dbs->fetchColumn( 0 )) !== false)
        {
            if($db == $this->_dbName)
            {
                return false;
            }
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Extension html step 1
     * 
     * @return html
     */
    protected function createExtensionHtml()
    {
        $html = '<html>';
        $html.= '<head>'.$this->writeCss().'</head>';
        $html.= '<body>';
        $html.= '<h1>'.$this->_title.'</h1>';
        $html.= '<h4>'.$this->_sub_title.'</h4>';
        $html.= '<div class="WizardStep active">Step 1</div> <div class="WizardStep">|</div> <div class="WizardStep">Step 2</div>';

        $html.= $this->form->open('/'.getInstance()->uri->getRequestUri(), array('method' => 'POST', 'name' => 'step1', 'id' => 'setup_wizard'));

        $html.= '<table class="modelTable">';
        $html.= '<tr>';
        $html.= '<th>Extension</th>';
        $html.= '<th>Status</th>';
        $html.= '</tr>';
        $html.= $this->_checkExtensions();
        $html.='</tbody>';
        $html.='</table>';

        $html.= $this->form->submit('submit_step1','Next Step','id="submit_step1"');
        $html.= $this->form->submit('submit_step1','Next Step','id="submit_disabled" style="display:none" disabled');
        $html.= $this->form->close();

        $html.= $this->writeScript();

        $html.= '<p></p>';
        $html.= '<p class="footer" style="font-size:11px;color:#006857;">* Please install above the requirements then click next. "Otherwise application will not  work correctly." </p>';
        $html.= "\n</body>";
        $html.= "\n</html>";

        echo $html;
    }

    // --------------------------------------------------------------------

    /**
     * Form html step 2
     * 
     * @return html
     */
    protected function createFormHtml()
    {
        $html = '<html>';
        $html.= '<head>'.$this->writeCss().'</head>';
        $html.= '<body>';
        $html.= '<h1>'.$this->_title.'</h1>';
        $html.= $this->form->getNotice(); 

        $html.= '<div class="WizardStep">Step 1</div> <div class="WizardStep">|</div> <div class="WizardStep active">Step 2</div>';
        
        ( ! empty($this->wizard) ? $html.= $this->wizard->getMessage() : '' );

        $html.= $this->form->open('/'.getInstance()->uri->getRequestUri(), array('method' => 'POST', 'name' => 'step2', 'id' => 'setup_wizard'));

        $html.= '<table class="modelTable">';
        $html.= '<tr>';
        $html.= '<th colspan="2">'.$this->_sub_title.'</th>';
        $html.= '</tr>';
        $html.= $this->createForm();
        $html.='</tbody>';
        $html.='</table>';
        
        $html.= $this->form->submit('back','Back');
        $html.= $this->form->submit('submit_step2','Install');
        $html.= $this->form->close();

        $html.= '<p></p>';
        $html.= '<p class="footer" style="font-size:11px;color:#006857;">'.$this->getNote().'</p>';
        $html.= "\n</body>";
        $html.= "\n</html>";

        echo $html;
    }

    // --------------------------------------------------------------------
    
    /**
     * Write javascript output
     * 
     * @return javascript
     */
    public function writeScript()
    {
        return '<script type="text/javascript">
                    var elm = document.getElementById("driverError");
                    if(elm !== null && elm.className == "columnTypeError"){
                        document.getElementById("submit_step1").style.display = "none";
                        document.getElementById("submit_disabled").style.display = "block";
                    }
                </script>';
    }

    // --------------------------------------------------------------------

    /**
     * Write css output
     * 
     * @return string css
     */
    public function writeCss()
    {
        return '<link href="'.$this->getCssFile().'" rel="stylesheet" type="text/css" />';
    }

}

/* End of file setup_wizard.php */
/* Location: ./packages/setup_wizard/releases/0.0.1/setup_wizard.php */