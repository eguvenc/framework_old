## Base Information <a name="base-information"></a>

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

### Obullo License Agreement <a name="obullo-license-agreement"></a>

------

Copyright (c) 2009 - 2010 Obullo.com, http://obullo.com All rights reserved.

This license is a legal agreement between you and Obullo.com for the use of Obullo Software (the "Software"). By obtaining the Software you agree to comply with the terms and conditions of this license.

### Permitted Use

------

You are permitted to use, copy, modify, and distribute the Software and its documentation, with or without modification, for any purpose, provided that the following conditions are met:
1. A copy of this license agreement must be included with the distribution.
2. Redistributions of source code must retain the above copyright notice in all source code files.
3. Redistributions in binary form must reproduce the above copyright notice in the documentation and/or other materials provided with the distribution.
4. Any files that have been modified must carry notices stating the nature of the change and the names of those who changed them.
5. Products derived from the Software must include an acknowledgment that they are derived from Obullo in their documentation and/or other materials provided with the distribution.
6. Products derived from the Software may not be called "Obullo", nor may "Obullo" appear in their name, without prior written permission from Obullo.com

### Indemnity

------

You agree to indemnify and hold harmless the authors of the Software and any contributors for any direct, indirect, incidental, or consequential third-party claims, actions or suits, as well as any related expenses, liabilities, damages, settlements or fees arising from your use or misuse of the Software, or a violation of any terms of this license.

### Disclaimer of Warranty

------

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE.

### Limitations of Liability

------

YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT OF, OR IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.

### Credits <a name="credits"></a>

------

Obullo Framework derived from Code Igniter. It is currently developed and maintained by Ersin Güvenç and his friends.

We love CodeIgniter ! , it was originally developed by Rick Ellis.