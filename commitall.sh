#!/bin/bash
ssh-agent;
ssh-add;

echo 'Commiting all packages !!';

for file in packages/*; do
	arr=$(echo $file | tr "/" "\n")
	for x in $arr
	do
		if [ $x != 'packages' && $x != 'index.html' ]; then
			cd /var/www/framework/packages/$x
			git commit -am 'updated package.json'
			git push origin master
		fi;
	done
done

# cd /var/www/obullo-2.0/packages/$1
# git commit -am 'updated package.json'
# git push origin master
