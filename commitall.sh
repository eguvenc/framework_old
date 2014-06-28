#!/bin/bash
ssh-agent;
ssh-add;

echo 'Commiting all versions !!';

for file in obullo/*; do
	arr=$(echo $file | tr "/" "\n")
	for x in $arr
	do
		if [ $x != 'obullo' ] && [ $x != 'index.html' ]; then
			cd /var/www/framework/obullo/$x
			echo $x 'updated';
			git commit -am 'updated package.'
			git push origin master
		fi;
	done
done

# cd /var/www/framework/obullo/$1
# git commit -am 'updated package.json'
# git push origin master
