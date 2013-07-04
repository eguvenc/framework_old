<?php
defined('STDIN') or die('Access Denied');

Class Export extends Controller {
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        echo "\33[0;36m".'
        _____      ________     __     __  __        __          _______
      / ___  /    / ____   \   / /    / / / /       / /         / ___   /
    /  /   /  /  / /____/  /  / /    / / / /       / /        /  /   /  /
   /  /   /  /  / _____  /   / /    / / / /       / /        /  /   /  /
  /  /___/  /  / /____/  \  / /____/ / / /____   / /_____   /  /__ /  /
  /_______/   /__________/ /________/ /_______/ /_______ /  /_______/ 
  
                       Welcome to Export Manager (c) 2013
Export your project [$php task export]'."\033[0m\n";

        $this->_export(); // Start the Export Task
    }

    /**
     * Start the Export Task Shell Script.
     */
    function _export()
    {
        $export_sh = "
        ####################
        #  E X P O R T. S H  ( Export entire project, delete logs, .svn and .git files to go on deployment )
        ####################

        # http://stackoverflow.com/questions/1371261/get-current-directory-name-without-full-path-in-bash-script
        PROJECT_NAME=\${PWD##*/}
        PROJECT_DIR=\${PWD}

        if [ ! -d ob_modules ]; then
            # Check the obullo directory exists, so we know you are in the project folder.
            echo \"You must be in the project folder root ! Try cd /your/www/path/projectname\".
            return
        fi

        APP_LOG_DIR=\"\$PROJECT_DIR/app/logs/\"

        # delete app directory log files.
        sudo find \$APP_LOG_DIR -name 'log-*.php' -exec rm -rf {} \;  # help https://help.ubuntu.com/community/find

        # copy the project to a new folder called \"projectname_export-date\"
        EXPORT_FOLDER=\"export_`date '+%Y-%m-%d_%H-%M'`\"

        # force delete .git or .svn files.
        sudo cp -R \$PROJECT_DIR/ /tmp/\$EXPORT_FOLDER/
        sudo cp -R /tmp/\$EXPORT_FOLDER/ \$PROJECT_DIR/\$EXPORT_FOLDER/

        # go to export folder and remove .svn and .git files.
        cd \$EXPORT_FOLDER
        sudo rm -rf `find . -type d -name .svn`
        sudo rm -rf `find . -type d -name .git`
        echo \"\33[0;32mExport task done, folder named as \$EXPORT_FOLDER.\33[0m\";";
        
        echo shell_exec($export_sh);
    }
    
}

/* End of file export.php */
/* Location: .modules/tasks/export.php */