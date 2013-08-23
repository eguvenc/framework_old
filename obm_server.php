<?php

Class Obm_Server {
    public $package_list = array();
    public $errors       = array();
    
    function __construct(){
        // Get the json file from package database 
        // and send it to user.
        // this area will come form MYSQL database.
    }
    function globalClientVersion(){
        return '0.1';
    }
    function run(){
        // Check the $USER Obm version if OBM version Out of Date
        // Send error and begin the upgrade process to new version.
        if($_REQUEST['_version'] != $this->globalClientVersion()){
            header('Access-Control-Max-Age: 3628800');
            header('Access-Control-Allow-Methods: GET, POST');
            header('Content-type: application/json');
            header('Cache-Control: no-cache, must-revalidate'); // NO CACHE
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            echo json_encode(array('version_update' => $this->globalClientVersion()));
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
                    $this->errors[] = "The package.json dependencies can't be empty. You need to add packages.";
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
                    if($this->packageExists($key)){
                        if( ! is_object($row[$key])){
                            $package_update_list[$key] = $this->getPackageJson($key);
                        }
                    }
                }
                echo json_encode($package_update_list);
                
                // echo json_encode($this->package_list);
            }
        // }    
    }
    
    function packageExists($name){
        $packages = array(
            'task' => '',
            'auth' => ''
        );
        if(isset($packages[$name])){
            return true;
        }
        return false;
    }
    
    function getPackageJson($name){
        ###
        ### connect to database and get TASK package.json file
        // mysql_fetch_row();
        
        if($name == 'auth')
        {
            return '{
                    "name": "auth",
                    "description": "User Authentication Class",
                    "version": "0.0.2",
                    "author": {
                      "name": "Ersin Güvenç",
                      "email": "eguvenc@gmail.com",
                      "url": "https://github.com/eguvenc"
                    },
                    "component" : "library",
                    "dependencies": {
                      "sess": "*"
                    },
                    "keywords": [
                      "auth",
                      "authentication"
                    ],
                    "homepage": "https://github.com/obullo/auth",
                    "repo": {
                      "type": "git",
                      "url": "git://github.com/obullo/auth.git",
                      "archive" : "zip",
                      "archive_url": "https://github.com/obullo/auth/archive/master.zip"
                    },
                    "requires": {
                      "php": ">= 5.1.2",
                      "extensions": ["none"]
                    },
                    "bugs": {
                      "url": "https://github.com/obullo/auth/issues",
                      "email": "eguvenc@gmail.com"
                    },
                    "licenses": [
                      {
                        "type": "GPL",
                        "url": "http://www.gnu.org/licenses/gpl-3.0.html"
                      }
                    ]
                  }';
        }
        
        if($name == 'task')
        {   
            return '{
                    "name": "task",
                    "description": "Task Helper, run cli tasks and follow debugs from command line.",
                    "version": "0.0.1",
                    "author": {
                      "name": "Ersin Güvenç",
                      "email": "eguvenc@gmail.com",
                      "url": "https://github.com/eguvenc"
                    },
                    "component" : "helper",
                    "shell" : [
                      {
                          "copy" : { 
                              "source" : "ob/$name/releases/$version/src/task", 
                              "target" : "/" 
                          },
                          "copy" : {
                              "source" : "ob/$name/releases/$version/example/*",
                              "target" : "modules/tasks/controllers"
                          }
                      }
                    ],
                    "keywords": [
                      "task",
                      "debug",
                      "debugging",
                      "cli",
                      "cli log debug",
                      "log debug",
                      "follow debugs"
                    ],
                    "homepage": "https://github.com/obullo/task",
                    "repo": {
                      "type": "git",
                      "url": "https://github.com/obullo/task.git",
                      "archive" : "zip",
                      "archive_url": "https://github.com/obullo/task/archive/master.zip"
                    },
                    "requires": {
                      "php": ">= 5.2.4",
                      "extensions": ["none"]
                    },
                    "bugs": {
                      "url": "https://github.com/obullo/task/issues",
                      "email": "eguvenc@gmail.com"
                    },
                    "licenses": [
                      {
                        "type": "GPL",
                        "url": "http://www.gnu.org/licenses/gpl-3.0.html"
                      }
                    ]
                  }';
        }
       
    }
    
}

$server = new Obm_Server();
$server->run();