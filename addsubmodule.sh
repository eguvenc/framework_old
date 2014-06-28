#!/bin/bash

	## Learning the Shell
	## http://linuxcommand.org/wss0090.php
	if [ -z $1 ]; then
		echo 'Package name could not be empty';
		exit 1;
	fi;

git submodule add git://github.com/obullo/$1.git obullo/$1
git submodule init
git submodule update
git commit -am 'added new submodule to this folder obullo/ '$1
git push origin master

cd /var/www/framework/obullo/$1
git checkout master
git remote set-url origin git@github.com:obullo/$1.git
chmod -R 777 /var/www/framework
cd /var/www/framework

echo 'Added new submodule called '$1;