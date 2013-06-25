<?php

Class Obm_Server {
    public $package_list = array();
    public $errors = array();
    
    function __construct(){
        // Get the json file from package database 
        // and send it to user.
        // this area will come form MYSQL database.
    }
    function global_client_version(){
        return '0.1';
    }
    function run(){
        // Check the $USER Obm version if OBM version Out of Date
        // Send error and begin the upgrade process to new version.
        if($_REQUEST['_version'] != $this->global_client_version()){
            header('Access-Control-Max-Age: 3628800');
            header('Access-Control-Allow-Methods: GET, POST');
            header('Content-type: application/json');
            header('Cache-Control: no-cache, must-revalidate'); // NO CACHE
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            echo json_encode(array('version_update' => $this->global_client_version()));
            return;
        }
        
        // get the MAIN package JSON
        // echo 'REQUEST DATA : ';
        // print_r($_REQUEST);  
        // if(isset($_GET[0]) AND $_GET[0] == 'update')
        //{
            $package_json = '';
            if(isset($_REQUEST['_json'])) {  // JSON is required field.
                $package_json = json_decode($_REQUEST['_json'], true);
                if( ! is_array($package_json) AND count($package_json) < 1){
                    $this->errors[] = "The package.json file seems empty or not formatted correctly.";
                }
                if(count($package_json['dependencies']) == 0){
                    $this->errors[] = "The package.json dependencies can't be empty.";
                }
                
            } else {
                $this->errors[] = "The package.json file seems does not exists in your project root.";
            }
            if ( ! extension_loaded('libxml') ){
                echo json_encode(array('errors' => 'Server Error: PECL libxml extension not loaded on the server.'));
                return;
            }
            header('Access-Control-Max-Age: 3628800');
            header('Access-Control-Allow-Methods: GET, POST');
            header('Content-type: application/json');
            header('Cache-Control: no-cache, must-revalidate'); // NO CACHE
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            
            $package_update_list = array();
            if(sizeof($this->errors) > 0){
               echo json_encode(array('errors' => $this->errors));
            } else {
                foreach($package_json['dependencies'] as $key => $row) {
                    if($this->package_exists($key)){
                        if( ! is_object($row[$key])){
                            $package_update_list[$key] = $this->get_package_json($key);
                        }
                    }
                }
                
                echo json_encode($package_update_list);
                
                // echo json_encode($this->package_list);
            }
        // }    
    }
    
    function package_exists($name){
        $packages = array(
            'auth' => ''
        );
        if(isset($packages[$name])){
            return TRUE;
        }
        return FALSE;
    }
    
    function get_package_json($name){
        return file_get_contents('obullo_modules/'.$name.'/package.json');
    }
    
}

$server = new Obm_Server();
$server->run();