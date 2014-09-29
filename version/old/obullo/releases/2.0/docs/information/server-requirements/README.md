### Server Requirements <a name="server-requirements"></a>

------

Obullo currently supports <b>PHP 5.3.0</b> and newer versions and recommends the most current release of [PHP]("http://www.php.net/") for critical security and performance enhancements.
Obullo currently use <kbd>Database_Pdo</kbd> component <b>PDO</b> for database operations. <b>Mysql</b> and <b>SQLite</b> drivers are enabled by default as of PHP 5.3.0 and newer versions.

Un-comment the PDO database file pdo.ini

```php
extension=pdo.so
```

and un-comment your driver file <b>pdo_mysql.ini</b>

```php
extension=pdo_mysql.so
```

For more details look at http://www.php.net/manual/en/pdo.installation.php

**Tip:** To edit your <dfn>app/config/database.php</dfn> , choose your <u>Obullo Connection Name</u> bottom of the table and change it like below the example.

```php
$database['db']['dbdriver'] = "mysql";
```

### Supported Database Types

------

<table class="span9">
<thead>
<tr>
<th>PDO Driver Name</th>
<th>Connection Name</th>
<th>Database Name</th>
</tr>
</thead>
<tbody>
<tr>
<td>PDO_DBLIB</td>
<td>dblib / mssql / sybase / freetds</td>
<td>FreeTDS / Microsoft SQL Server / Sybase</td>
</tr>
<tr>
<td>PDO_FIREBIRD</td>
<td>firebird</td>
<td>Firebird/Interbase 6</td>
</tr>
<tr>
<td>PDO_IBM</td>
<td>ibm / db2</td>
<td>IBM DB2</td>
</tr>
<tr>
<td>PDO_MYSQL</td>
<td>mysql</td>
<td>MySQL 3.x/4.x/5.x</td>
</tr>
<tr>
<td>PDO_OCI</td>
<td>oracle / (or alias oci)</td>
<td>Oracle Call Interface</td>
</tr>
<tr>
<td>PDO_ODBC</td>
<td>odbc</td>
<td>ODBC v3 (IBM DB2, unixODBC and win32 ODBC)</td>
</tr>
<tr>
<td>PDO_PGSQL</td>
<td>pgsql</td>
<td>PostgreSQL</td>
</tr>
<tr>
<td>PDO_SQLITE</td>
<td>sqlite / sqlite2 / sqlite3</td>
<td>SQLite 3 and SQLite 2</td>
</tr>
<tr>
<td>PDO_4D</td>
<td>4d</td>
<td>4D</td>
</tr>
<tr>
<td>PDO_CUBRID</td>
<td>cubrid</td>
<td>Cubrid</td>
</tr>
</tbody>
</table>

### Supported NOSQL Database Types

------

<table class="span9">
<thead>
<tr>
<th>Driver Name</th>
<th>Connection Name</th>
<th>Database Name</th>
</tr></thead>
<tbody>
<tr>
<td>mongodb</td>
<td>mongo</td>
<td>MONGO</td>
</tr>
</tbody>
</table>

Framework has a config file that lets you store your database connection values (username, password, database name, etc.). The config file is located in:

<kbd>app/config/database.php</kbd>

The configuration is stored in a multi-dimensional array with this prototype:

```php
$database['db']['hostname'] = "localhost";
$database['db']['username'] = "root";
$database['db']['password'] = "";
$database['db']['database'] = "example_db";
$database['db']['driver']   = "mysql";
$database['db']['dbh_port'] = "";
$database['db']['char_set'] = "utf8";
$database['db']['dsn']      = "";
$database['db']['options']  = array();
```