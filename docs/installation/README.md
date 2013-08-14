
## Installation Steps

Obullo is installed in five steps:

1. Unzip the package.
2. Upload the Obullo folders and files to your server. Normally the index.php file will be at your root.
3. Open the **app/config/config.php** file with a text editor and set your base URL. If you intend to use encryption or sessions, set your encryption key.
4. If you intend to use a database, Obullo use PDO for database operations. Mysql and SQLite drivers installed as default for PHP 5 and newer versions.
If you want use another Db driver you must enable your PDO Driver in your php.ini file.For more details look at Php.net http://www.php.net/manual/en/pdo.installation.php
5. Open the **app/config/database.php** file with a text editor and set your database settings.

If you wish to increase security by hiding the location of your Obullo files you can rename the base folder to something more private. If you do rename it, you must open your main index.php file and set the BASE constant in the page with the new name you've chosen.

That's it!

If you're new to Obullo, please read the [Getting Started](http://obullo.com/user_guide/en/1.0.1/getting-started.html) section of the Manual to begin learning how to build dynamic PHP applications.

[We have a wiki page installation instructions for Ubuntu OS.](http://wiki.obullo.com/#setting_up_php_and_obullo_framework_under_the_ubuntu)

## Installation *-nix <a name="installation-nix"></a>


### Downloading the Composer Executable

------

#### Locally

To actually get Composer, we need to do two things. The first one is installing Composer (again, this means downloading it into your project):

```
$ curl -sS https://getcomposer.org/installer | php
```

This will just check a few PHP settings and then download <dfn>composer.phar</dfn> to your working directory. This file is the Composer binary. It is a PHAR (PHP archive), which is an archive format for PHP which can be run on the command line, amongst other things.

You can install Composer to a specific directory by using the <dfn>--install-dir</dfn> option and providing a target directory (it can be an absolute or relative path):

```
$ curl -sS https://getcomposer.org/installer | php -- --install-dir=bin
```

#### Globally

You can place this file anywhere you wish. If you put it in your <dfn>PATH</dfn>, you can access it globally. On unixy systems you can even make it executable and invoke it without <dfn>php</dfn>.

You can run these commands to easily access <dfn>composer</dfn> from anywhere on your system:

```
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer
```

**Note:** If the above fails due to permissions, run the <dfn>mv</dfn> line again with sudo.
Then, just run <dfn>composer</dfn> in order to run Composer instead of <dfn>php composer.phar</dfn>.

### Using Composer

------

We will now use Composer to install the dependencies of the project. If you don't have a <dfn>composer.json</dfn> file in the current directory please skip to the Basic Usage chapter.

To resolve and download dependencies, run the <dfn>install</dfn> command:

```
$ php composer.phar install
```

If you did a global install and do not have the phar in that directory run this instead:

```
$ composer install
```

Following the example above, this will download monolog into the vendor/monolog/monolog directory.