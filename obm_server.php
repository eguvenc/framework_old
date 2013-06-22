<?php

Class Obm_Server {
    
    function __construct(){
        
    }
    
    function run(){
        print_r($_GET);
    }
}

$server = new Obm_Server();
$server->run();