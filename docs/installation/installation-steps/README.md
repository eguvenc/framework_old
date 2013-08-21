## Installation Steps <a name="installation-steps"></a>

Obullo is installed in five steps:

1. Unzip the package.
2. Upload the Obullo folders and files to your server. Normally the index.php file will be at your root.
3. Open the **app/config/config.php** file with a text editor and set your base URL. If you intend to use encryption or sessions, set your encryption key.
4. If you intend to use a database, Obullo use PDO for database operations. Mysql and SQLite drivers installed as default for PHP 5 and newer versions.
If you want use another Db driver you must enable your PDO Driver in your php.ini file.For more details look at Php.net http://www.php.net/manual/en/pdo.installation.php
5. Open the **app/config/database.php** file with a text editor and set your database settings.

If you wish to increase security by hiding the location of your Obullo files you can rename the base folder to something more private. If you do rename it, you must open your main index.php file and set the BASE constant in the page with the new name you've chosen.

That's it!

If you're new to Obullo, please read the [Getting Started](/docs/introduction/getting-started) section of the Manual to begin learning how to build dynamic PHP applications.

[We have a wiki page installation instructions for Ubuntu OS.](http://wiki.obullo.com/#setting_up_php_and_obullo_framework_under_the_ubuntu)