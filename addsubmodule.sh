#!/bin/bash
ssh-agent;
ssh-add;

	## Learning the Shell
	## http://linuxcommand.org/wss0090.php
	if [ -z $1 ]; then
		echo 'Package name could not be empty';
		exit 1;
	fi;

git submodule add git://github.com/obullo/$1.git packages/$1
git submodule init
git submodule update
git commit -am 'added new submodule '$1
git push origin master

cd /var/www/framework/packages/$1
git checkout master
git remote set-url origin git@github.com:obullo/$1.git
cd /var/www/framework

echo 'Added new submodule '$1;