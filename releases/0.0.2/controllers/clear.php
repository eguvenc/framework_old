<?php
defined('STDIN') or die('Access Denied');

Class Clear extends Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $this->_clear(); // Start the Clear Task
    }
    
    function _clear()
    {
        $clear_sh = "
        ####################
        #  CLEAR. S H  ( Clear all application log files )
        ####################

        PROJECT_DIR=\${PWD}

        if [ ! -d ".PACKAGES." ]; then
            # Check the obullo directory exists, so we know you are in the project folder.
            echo \"You must be in the project folder root ! Try cd /your/www/path/projectname\".
            return
        fi

        # define your paths.
        APP_LOG_DIR=\"\$PROJECT_DIR/app/logs/\"

        # delete app directory log files.
        # help https://help.ubuntu.com/community/find
        find \$APP_LOG_DIR -name 'log-*.php' -exec rm -rf {} \;
        echo \"\33[0m\33[0;32mLog files deleted.\33[0m\";";
        
        echo shell_exec($clear_sh);
    }
    
}

/* End of file clear.php */
/* Location: .modules/tasks/controller/clear.php */