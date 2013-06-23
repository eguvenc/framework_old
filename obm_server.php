<?php

Class Obm_Server {
    
    public $package_list = array();
    public $errors = array();
    
    function __construct(){
        // Get the json file from package database 
        // and send it to user.
        
        // this area will come form MYSQL database.
        $this->package_list['task']   = file_get_contents('obullo_modules/task/package.json');
        $this->package_list['view']   = NULL;
        $this->package_list['auth']   = file_get_contents('obullo_modules/auth/package.json');
        $this->package_list['config'] = NULL;
    }
    
    function run(){
        
        // get the MAIN package JSON
        echo 'REQUEST DATA : ';
        print_r($_REQUEST);  
        // if(isset($_GET[0]) AND $_GET[0] == 'update')
        //{
            $package_json = '';
            if(isset($_REQUEST['json']))  // JSON is required field.
            {
                $package_json = json_decode($_REQUEST['json'], true);
                
                if( ! is_array($package_json) AND count($package_json) < 1)
                {
                    $this->errors[] = "The package.json file seems empty or not formatted correctly.";
                    $this->errors[] = "The package.json file seems does not exists in your project root.";
                }
            } 
            else
            {
                $this->errors[] = "The package.json file seems does not exists in your project root.";
            }
            
            if ( ! extension_loaded('libxml') )
            {
                die('Server Error: PECL libxml extension not loaded on the server.');
            }
            
            $xml = new XMLWriter();
            $xml->openMemory();
            $xml->startDocument('1.0', 'UTF-8');
            $xml->startElement('root');
                    
            if(sizeof($this->errors) > 0)
            {
                foreach($this->errors as $error)
                {
                    $this->_write_xml($xml, array('error' => $error), true);
                }
            } 
            else 
            {
                $this->_write_xml($xml, $this->package_list, true);
            }
            
            $xml->endElement();

            header("Content-type: text/xml; charset=utf-8");
            
            echo $xml->outputMemory(true);
        // }    
    }
    
    private function _write_xml($xml, $data, $cdata)
    {
        foreach($data as $key => $value)
        {
            if(is_array($value))
            {
               $xml->startElement($key);
               
                _write_xml($xml, $value, $cdata);
                
               $xml->endElement();

               continue;
            }

            if($cdata) // full CDATA tags
            {
                $xml->startElement($key);
                $xml->writeCData($value);
                $xml->endElement();
            } 
            else
            {   
                $xml->writeElement($key, $value);
            }
        }
    }
}

$server = new Obm_Server();
$server->run();