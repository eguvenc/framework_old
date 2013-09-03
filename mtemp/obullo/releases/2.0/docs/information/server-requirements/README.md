### Server Requirements <a name="server-requirements"></a>

------

    Obullo currently supports <b>PHP 5.3.0</b> and newer versions and recommends the most current release of [PHP]("http://www.php.net/") for critical security and performance enhancements.
    Obullo use <b>PDO (Php Data Objects)</b> for database operations. <b>Mysql</b> and <b>SQLite</b> drivers is enabled by default as of PHP 5.3.0 and newer versions.

Un-comment the PHP5 PDO database interface drivers in PHP.ini

```php
extension=php_pdo.dll
```

and un-comment your driver file

```php
extension=php_yourdriver.dll
```

on linux servers file extension is <b>.so</b>.

Look at for more details http://www.php.net/manual/en/pdo.installation.php

**Tip:** To edit your <dfn>app/config/database.php</dfn> , choose your <u>Obullo Connection Name</u> bottom of the table and change it like below the example.

```php
$database['db']['dbdriver'] = "mysql";
```
### Supported Database Types

------

<table><thead><tr>
<th>PDO Driver Name</th><th>Obullo Connection Name</th><th>Database Name</th></tr><tbody>
<tr><td>PDO_DBLIB</td><td>dblib / mssql / sybase / freetds</td><td>FreeTDS / Microsoft <tr><td>SQL Server / Sybase</td></tr>
<tr><td>PDO_FIREBIRD</td><td>firebird</td><td>Firebird/Interbase 6</td></tr>
<tr><td>PDO_IBM</td><td>ibm / db2</td><td>IBM DB2</td></tr>
<tr><td>PDO_MYSQL</td><td>mysql</td><td>MySQL 3.x/4.x/5.x</td></tr>
<tr><td>PDO_OCI</td><td>oracle / (or alias oci)</td><td>Oracle Call Interface</td></tr>
<tr><td>PDO_ODBC</td><td>odbc</td><td>ODBC v3 (IBM DB2, unixODBC and win32 ODBC)</td></tr>
<tr><td>PDO_PGSQL</td><td>pgsql</td><td>PostgreSQL</td></tr>
<tr><td>PDO_SQLITE</td><td>sqlite / sqlite2 / sqlite3</td><td>SQLite 3 and SQLite 2</td></tr>
<tr><td>PDO_4D</td><td>4d</td><td>4D</td></tr>
<tr><td>PDO_CUBRID</td><td>cubrid</td><td>CUBRID</td></tr></tbody></table>