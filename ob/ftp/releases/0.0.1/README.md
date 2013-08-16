## FTP Class


Obullo's FTP Class permits files to be transfered to a remote server. Remote files can also be moved, renamed, and deleted. The FTP class also includes a "mirroring" function that permits an entire local directory to be recreated remotely via FTP.

**Note:**  SFTP and SSL FTP protocols are not supported, only standard FTP.

### Initializing the Class

------

```php
new Ftp();
```
Once loaded, the FTP object will be available using: <dfn>$this->ftp->method()</dfn>

### Grabbing the Instance

------

Also using new Ftp(false); boolean you can grab the instance of Obullo libraries,"$this->ftp->method()" will not available in the controller.

```php
$ftp = new Ftp(false);

$ftp->method();
```

### Usage Examples

------

In this example a connection is opened to the FTP server, and a local file is read and uploaded in ASCII mode. The file permissions are set to 755. Note: Setting permissions requires PHP 5.

```php
$config['hostname'] = 'ftp.example.com';
$config['username'] = 'your-username';
$config['password'] = 'your-password';
$config['debug']    = TRUE;

$this->ftp->connect($config);

$this->ftp->upload('/local/path/to/myfile.html', '/public_html/myfile.html', 'ascii', 0775);

$this->ftp->close(); 
```

In this example a list of files is retrieved from the server.

```php
$config['hostname'] = 'ftp.example.com';
$config['username'] = 'your-username';
$config['password'] = 'your-password';
$config['debug'] = TRUE;

$this->ftp->connect($config);

$list = $this->ftp->listFiles('/public_html/');

print_r($list);

$this->ftp->close();
```

In this example a local directory is mirrored on the server.

```php
$config['hostname'] = 'ftp.example.com';
$config['username'] = 'your-username';
$config['password'] = 'your-password';
$config['debug'] = TRUE;

$this->ftp->connect($config);

$this->ftp->mirror('/path/to/myfolder/', '/public_html/myfolder/');

$this->ftp->close(); 
```

### Function Reference

------

#### $this->ftp->connect()

------

Connects and logs into to the FTP server. Connection preferences are set by passing an array to the function, or you can store them in a config file.

Here is an example showing how you set preferences manually:

```php
$config['hostname'] = 'ftp.example.com';
$config['username'] = 'your-username';
$config['password'] = 'your-password';
$config['port']     = 21;
$config['passive']  = FALSE;
$config['debug']    = TRUE;

$this->ftp->connect($config);

$this->ftp->close(); 
```

### Setting FTP Preferences in a Config File

If you prefer you can store your FTP preferences in a config file. Simply create a new file called the <var>ftp.php</var>, add the <var>$config</var> array in that file. Then save the file at <var>config/ftp.php</var> and it will be used automatically.

### Available connection options:

    <ul><li><strong>hostname</strong> - the FTP hostname. Usually something like:  <dfn>ftp.example.com</dfn></li>    <li><strong>username</strong> - the FTP username.</li>
    <li><strong>password</strong> - the FTP password.</li>
    <li><strong>port</strong> - The port number. Set to 21 by default.</li>
    <li><strong>debug</strong> - <kbd>TRUE/FALSE</kbd> (boolean). Whether to enable debugging to display error messages.</li>
    <li><strong>passive</strong> - <kbd>TRUE/FALSE</kbd> (boolean). Whether to use passive mode. Passive is set automatically by default.</li></ul>

#### $this->ftp->upload()

------

Uploads a file to your server. You must supply the local path and the remote path, and you can optionally set the mode and permissions. Example:

```php
$this->ftp->upload('/local/path/to/myfile.html', '/public_html/myfile.html', 'ascii', 0775);
```

<strong>Mode options are:</strong>  <kbd>ascii, binary</kbd>, and <kbd>auto</kbd> (the default). If <kbd>auto</kbd> is used it will base the mode on the file extension of the source file.

Permissions are available if you are running PHP 5 and can be passed as an <kbd>octal</kbd> value in the fourth parameter.

#### $this->ftp->rename()

------

Permits you to rename a file. Supply the source file name/path and the new file name/path.

```php
// Renames green.html to blue.html
$ftp->rename('/public_html/foo/green.html', '/public_html/foo/blue.html'); 
```

#### $this->ftp->move()

------

Lets you move a file. Supply the source and destination paths:

```php
// Moves blog.html from "joe" to "fred"
$this->ftp->move('/public_html/joe/blog.html', '/public_html/fred/blog.html');
```

Note: if the destination file name is different the file will be renamed.

#### $this->ftp->deleteFile()

------

Lets you delete a file. Supply the source path with the file name.

```php
$this->ftp->delete_file('/public_html/joe/blog.html');
```

#### $this->ftp->deleteDir()

------

Lets you delete a directory and everything it contains. Supply the source path to the directory with a trailing slash.

*Important*  Be VERY careful with this function. It will recursively delete <b>everything</b> within the supplied path, including sub-folders and all files. Make absolutely sure your path is correct. Try using the <kbd>list_files()</kbd> function first to verify that your path is correct.

```php
$this->ftp->delete_dir('/public_html/path/to/folder/');
```

#### $this->ftp->listFiles()

------

Permits you to retrieve a list of files on your server returned as an <dfn>array</dfn>. You must supply the path to the desired directory.

```php
$list = $this->ftp->list_files('/public_html/');
print_r($list);
```

#### $this->ftp->mirror()

------

Recursively reads a local folder and everything it contains (including sub-folders) and creates a mirror via FTP based on it. Whatever the directory structure of the original file path will be recreated on the server. You must supply a source path and a destination path:

```php
$this->ftp->mirror('/path/to/myfolder/', '/public_html/myfolder/');
```

#### $this->ftp->mkdir()

------

Lets you create a directory on your server. Supply the path ending in the folder name you wish to create, with a trailing slash. Permissions can be set by passed an <kbd>octal</kbd> value in the second parameter.

```php
// Creates a folder named "bar"
$this->ftp->mkdir('/public_html/foo/bar/', DIR_WRITE_MODE);
```

#### $this->ftp->chmod()

------

Permits you to set file permissions. Supply the path to the file or folder you wish to alter permissions on:

```php
// Chmod "bar" to 777
$this->ftp->chmod('/public_html/foo/bar/', DIR_WRITE_MODE);
```

#### $this->ftp->close();

------

Closes the connection to your server. It's recommended that you use this when you are finished uploading.