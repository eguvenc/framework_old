#!/bin/sh

cd /tmp

wget -O framework.zip https://github.com/obullo/framework/zipball/dev
unzip framework.zip
mv obullo-framework* framework

wget -O app.zip https://github.com/obullo/app/zipball/dev
unzip app.zip
mv obullo-app* app

wget -O obullo.zip https://github.com/obullo/obullo/zipball/dev
unzip obullo.zip
mv obullo-obullo* obullo

#EXPORT_FOLDER="obullo-latest-`date '+%Y-%m-%d_%H-%M'`"
#sudo mkdir $EXPORT_FOLDER

EXPORT_FOLDER="obullo-latest-beta"

cp -R app /tmp/framework/
cp -R obullo /tmp/framework/

zip -r $EXPORT_FOLDER.zip framework
rm -rf $EXPORT_FOLDER
mv $EXPORT_FOLDER.zip /var/www/framework/$EXPORT_FOLDER.zip

sudo chmod 777 /var/www/framework/$EXPORT_FOLDER.zip

# delete old files.
rm -rf framework.zip
rm -rf app.zip
rm -rf obullo.zip
rm -rf framework
rm -rf app
rm -rf obullo