#!/bin/bash
ssh-agent;
ssh-add;

echo 'Pulling all packages !!';

for file in packages/*; do
	arr=$(echo $file | tr "/" "\n")
	for x in $arr
	do
		if [ $x != 'packages' ] && [ $x != 'index.html' ]; then
			cd /var/www/framework/packages/$x
			echo $x 'pulled';
			git remote set-url origin git@github.com:obullo/$x.git
			git checkout master
			git pull origin master
		fi;
	done
done

# pull all pages
