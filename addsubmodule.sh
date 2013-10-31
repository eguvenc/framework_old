#!/bin/bash

	## Learning the Shell
	## http://linuxcommand.org/wss0090.php
	if [ -z $1 ]; then
		echo 'Package name could not be empty';
		exit 1;
	fi;

git submodule add git://github.com/obullo/$1.git modules/packages/$1
git submodule init
git submodule update
git commit -am 'added new submodule modules/package/ '$1
git push origin master

cd /var/www/framework/modules/packages/$1
git checkout master
git remote set-url origin git@github.com:obullo/$1.git
chmod -R 777 /var/www/framework
cd /var/www/framework

echo 'Added new submodule '$1;
