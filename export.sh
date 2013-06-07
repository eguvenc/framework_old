#!/bin/sh

####################
#  E X P O R T. S H  ( Export entire project, delete logs, .svn and .git files to go on deployment )
####################

# http://stackoverflow.com/questions/1371261/get-current-directory-name-without-full-path-in-bash-script
PROJECT_NAME=${PWD##*/}
PROJECT_DIR=${PWD}

if [ ! -d obullo ]; then
    # Check the obullo directory exists, so we know you are in the project folder.
    echo "You must be in the project folder root ! Try cd /your/www/path/projectname".
    return
fi

APP_LOG_DIR="$PROJECT_DIR/app/core/logs/"
MOD_LOG_DIR="$PROJECT_DIR/modules/"

# delete application and module directory log files.
find $APP_LOG_DIR -name 'log-*.php' -exec rm -rf {} \;  # help https://help.ubuntu.com/community/find
find $MOD_LOG_DIR -name 'log-*.php' -exec rm -rf {} \;

# copy the project to a new folder called "projectname_export-date"
EXPORT_FOLDER="export_`date '+%Y-%m-%d_%H-%M'`"

# force delete .git or .svn files.
cp -R $PROJECT_DIR/ /tmp/$EXPORT_FOLDER/
cp -R /tmp/$EXPORT_FOLDER/ $PROJECT_DIR/$EXPORT_FOLDER/

# go to export folder and remove ".svn" and ".git" files.
cd $EXPORT_FOLDER
rm -rf `find . -type d -name .svn`
rm -rf `find . -type d -name .git`

echo "\33[0;32mExport process named as $EXPORT_FOLDER and completed !\33[0m"