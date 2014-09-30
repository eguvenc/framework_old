## Full Installation Steps <a name="installation-steps"></a>

This documentation will help you to <b>start a project with Obullo</b> on unixy systems. This is a througly documentation prepared for <kbd>MacOS \ Ubuntu</kbd> and <kbd>Debian</kbd> based OS.

### Installing Php & MySQL

First of all install <b>GCC</b> and <b>g++</b> libraries and update your OS.

```php
sudo apt-get install build-essential
sudo apt-get install aptitude
sudo apt-get update
```

#### Installing Php Packages

```php
sudo apt-get install php5 php5-common libapache2-mod-php5 php5-gd php5-dev curl libcurl3 libcurl3-dev php5-curl php5-mysql php5-mcrypt
```

#### Installing MySQL Server

```php
sudo apt-get install mysql-server
```

Restart the apache web server

```php
service apache2 restart
```

#### Installing PhpMyAdmin

```php
sudo apt-get install phpmyadmin
```

Find allow no password line an change it if you want to use "no password" in localhost.

```php
sudo gedit /etc/phpmyadmin/config.inc.php
```

Change it like this

```php
$AllowNoPassword = TRUE;
```

Creating shorcut (symbolic link) to <b>/var/www</b> folder

```php
ln -s /usr/share/phpmyadmin /var/www/phpmyadmin
```

Phpmyadmin now available in here

```php
http://localhost/phpmyadmin/
```

### Creating Your Project

Create your project folder to <kbd>/var/www</kbd> path.

```php
mkdir /var/www/projectname
```

Define a hostname to your project editing your linux host file with your favourite editor.

```php
sudo vi /etc/hosts
```
Add your project name and save it.

```php
127.0.0.1       localhost
127.0.0.1       myproject
```

### Creating Apache Virtual Host for Your Project

Go to your apache sites folder

```php
cd /etc/apache2/sites-available
```

Copy default vhost file as your project name

```php
sudo cp default myproject
```

**Note:** If you use latest version of Apache2 you may need call some configuration files with <b>.conf</b> extension.

```php
sudo cp default.conf myproject.conf
```

Open <b>myproject</b> vhost file and edit it.

```php
sudo vi myproject
```

You need to do below the changes in <b>myproject</b> vhost file.

* Replace all <b>AllowOverride None</b> lines to <b>AllowOverride All</b>.
* Add <b>ServerName myproject</b> after first line ( ServerAdmin webmaster@localhost ).
* Edit document root line as "DocumentRoot /var/www/myproject".

```php
<VirtualHost *:80>
	ServerAdmin webmaster@localhost
	ServerName myproject

	DocumentRoot /var/www/myproject
	<Directory />
		Options FollowSymLinks
		AllowOverride All
	</Directory>
	<Directory /var/www/>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>

	ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
	<Directory "/usr/lib/cgi-bin">
		AllowOverride All
		Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
		Order allow,deny
		Allow from all
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### Enabling Your Virtual Host

Below the command will create a symbolic link in <kbd>/etc/apache2/sites-enabled</kbd> folder. All enabled sites is located in this folder.

```php
sudo a2ensite myproject
```

To activate the your new configuration, you need to run:

```php
sudo service apache2 restart
```

Congratulations ! Now your project available in here.

```php
http://myproject/
```

### Disabling Your Virtual Host

To deactivate the site you need run this command.

```php
sudo a2dissite myproject
sudo service apache2 restart
```

### Enable Apache Mod Rewrite and Remove index.php

If Apache <kbd>mod_rewrite</kbd> module <b>not</b> installed on your machine you can't use <b>.htaccess</b> features.

To fix that problem first check apache mod_rewrite module is installed.

```php
cd /etc/apache2/mods-enabled
```

To list all installed apache modules type <b>"ll"</b> command.

```php
ll
lrwxrwxrwx 1 root root   28 June 17 17:59 alias.conf -> ../mods-available/alias.conf
lrwxrwxrwx 1 root root   28 June 17 17:59 alias.load -> ../mods-available/alias.load
lrwxrwxrwx 1 root root   33 June 17 17:59 auth_basic.load -> ../mods-available/auth_ba
```

If you couldn't see in the list <b>rewrite.load</b> you need to enable it

```php
sudo a2enmod rewrite
sudo service apache2 restart
```

##### Removing index.php file using .htaccess

Rename the <kbd>example.htaccess</kbd> file as <kbd>.htaccess</kbd> which is located in our project root.

```php
mv example.htaccess .htaccess
```
Edit your <b>index_page</b> config:

Open <kbd>app/config/config.php</kbd> and set <b>index_page</b> to blank.

```php
$config['index_page'] = "";
```

### Troubleshooting

##### Forbidden Error

When you visit the <kbd>http://myproject/</kbd> you might have probably faced the same following error

```php
Forbidden

You don't have permission to access / on this server.
```

To fix that problem change your project <b>permissions</b> to <b>755</b>.

```php
sudo chmod 755 -R /var/www/myproject/
```

##### Apache Errors

You might have probably faced the same following error while you were restarting the Apache server.

```php
* Restarting web server apache2

apache2: Could not reliably determine the server's fully qualified domain name, using 127.0.1.1 for ServerName

... waiting apache2: Could not reliably determine the server's fully qualified domain name, using 127.0.1.1 for ServerName
```

To fix that problem: 

if your Ubuntu version <b>13.0</b> or newer you need to do this


```php
cd /etc/apache2/conf-available
vi general-sites.conf
```

Copy paste below the content

```php
ServerName localhost 
AddType application/x-httpd-php .php .html .htm 
DirectoryIndex index.html index.htm index.php
```

then save file exit and enable your configuration

```php
a2enconf general-sites
```

Restart the webserver

```php
service apache2 restart
```

if your Ubuntu version close to <b>12.0</b> you need to do this

```php
sudo sh -c 'echo "ServerName localhost" >> /etc/apache2/conf.d/name' && sudo service apache2 restart
```

If you use <b>old versions</b> do below the steps:

You need to edit the httpd.conf file. Open the terminal and type:

```php
sudo gedit /etc/apache2/httpd.conf
```

By default <b>httpd.conf</b> file will be blank. Now, simply add the following line to the file.

```php
ServerName localhost
```

Save the file and exit from gedit. Finally restart the server.

```php
sudo service apache2 restart
```


##### Php Short Open Tag Issue

If you use short open php tags like below

```php
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <? echo Html::css('welcome.css') ?>
        <title>Obullo</title>
    </head>
```

You might have probably faced the php syntax errors while you were browsing your application.

To fix that problem open your php.ini file and set <kbd>short_open_tag</kbd> value to <kbd>On</kbd>.

```php
cd /etc/php5/apache2
sudo gedit php.ini
```

```php
; Development Value: Off
; Production Value: Off
; http://php.net/short-open-tag
short_open_tag = On
```

### Installing the Obullo

------

#### Locally

```php
wget https://obullo.com/installer
```

This will just download the <kbd>obm</kbd> file to your working directory. This file is a native PHP file which can be run on the command line. After downloading you can run the obm.


```php
php obm install
```

#### Globally

If you put it in your <kbd>/usr/local/bin</kbd> path, you can access it globally. On unixy systems you can even make it executable and invoke it without <b>php</b>.

You can run these commands to easily access <kbd>obm</kbd> from anywhere on your system:

```php
wget https://obullo.com/installer
mv obm /usr/local/bin/obm
```

**Note:** If the above fails due to permissions, run the <kbd>mv</kbd> line again with <b>sudo</b>. Then, just run <kbd>obm</kbd> in order to run <kbd>obm</kbd> instead of <kbd>php obm</kbd>.

### Using Obm

------

We will now use Obm to install the dependencies and core components of the framework , run the <kbd>install</kbd> command:

```php
php obm install
```

If you did a global install and do <b>not</b> have the <kbd>obm</kbd> in that directory run this instead:

```php
obm install
```

To update your packages run the obm update.

```php
obm update
```

#### Adding your packages

Following the example below, this will download <b>Pager Package</b> into the <kbd>packages/pager</kbd> directory.

Update the <b>package.json</b> defined in your project root. For example add <b>auth</b> package to your project.

```php
{
    "dependencies": {
        "obullo": "*",
        "auth" : "*"
    }
}
```
After editing the <b>package.json</b> you need to run <kbd>obm update</kbd>.

#### Updating your packages

The <b>asterisks ( * )</b> means getting the <kbd>latest version</kbd> of the package. If you need a previous stable version remove the asterisk ( * ) and set it to a specific number. ( e.g. auth: "0.0.2" ).

```php
{
    "dependencies": {
        "auth": "0.0.2",
        "bench" : "*",
        "config" : "*",
        "database_pdo": "*",
        "error" : "*",
        "exceptions" : "*",
        "form" : "*",
        "i" : "*",
        "input" : "*",
        "locale" : "*",
        "log" : "*",
        "router" : "*",
        "security": "*",
        "obullo": "2.0",
        "output" : "*",
        "uri" : "*",
        "url" : "*",
        "view" : "*",
        "validator" : "*"
  }
}
```

After editing the <b>package.json</b> you need to run <kbd>obm update</kbd>.


#### Replacing Core Components

The components 
if available, you can replace the core components with third-party packages from the obm repository. ( http://obm.obullo.com ). Below the example we set the <b>log</b> component to <b>Thirdparty_Logwrite</b> package.

```php
{
   "dependencies": {
        "Thirdparty_Logwrite": "*"
        "db": "*",
        "database_pdo"
        
   },
   "components": {
        "log": "Thirdparty_Logwrite",  // Log_Write
        "db": "Database_Pdo"
  }
}
```

After editing the <b>package.json</b> you need to run <kbd>obm update</kbd>.