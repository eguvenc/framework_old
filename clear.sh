#!/bin/sh

####################
#  C L E A R . S H  ( Clear all temporary files using one command ).
####################

# detect your project path.
PROJECT_DIR=${PWD}

if [ ! -d obullo ]; then
    # Check the obullo directory exists, so we know you are in the project folder.
    echo "You must be in the project folder root ! Try cd /your/www/path/projectname".
    return
fi

# define your paths.
APP_LOG_DIR="$PROJECT_DIR/app/core/logs/"
MOD_LOG_DIR="$PROJECT_DIR/modules/"

# delete application and module directory log files.
# help https://help.ubuntu.com/community/find
find $APP_LOG_DIR -name 'log-*.php' -exec rm -rf {} \;
find $MOD_LOG_DIR -name 'log-*.php' -exec rm -rf {} \;

echo "\33[0;32mGreat, temporary files clear job done !\33[0m"