<?php

return array(

        /**
         * Url
         * 
         * Baseurl : Base Url "/" URL of your framework root, generally a '/' trailing slash. 
         * assets
         *     url    : Assets url of your framework generally a '/' you may want to change it with your "cdn" provider.
         *     folder : Full path of assets folder
         * rewrite : 
         *     index.php : Typically this will be your index.php file, If mod_rewrite enabled is should be blank.
         */

        'params' => [
        
            'baseurl'  => '/',
            'assets'   => [
                'url' => '/',
                'folder' => '/resources/assets/',
            ],    
            'rewrite' => [
                'index.php' => ''
            ]
        ]
    );