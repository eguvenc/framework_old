## Zip Class

------

Obullo's Zip Encoding Class classes permit you to create Zip archives. Archives can be downloaded to your desktop or saved to a directory.

### Initializing the Class

------

Unlike most other classes in Obullo, the Zip class is initialized in your controller using the <dfn>$this->zip = zip::instance();</dfn> function:

```php
new Zip();
```

Once loaded, the Zip library object will be available using: <dfn>$this->zip->method();</dfn>

### Quick Access To Library

------

Also using new Zip(false); function you grab the instance of Obullo libraries.

```php
$zip = new Zip(false);
$zip->method();
```

### Usage Example

------

This example demonstrates how to compress a file, save it to a folder on your server, and download it to your desktop.

```php
new Zip();

$name = 'mydata1.txt';
$data = 'A Data String!';

$this->zip->addData($name, $data);

// Write the zip file to a folder on your server. Name it "my_backup.zip"

$this->zip->archive('/path/to/directory/my_backup.zip');

// Download the file to your desktop. Name it "my_backup.zip" 
$this->zip->download('my_backup.zip'); 
```

### Function Reference

-------

#### $this->zip->addData()

-------

Permits you to add data to the Zip archive. The first parameter must contain the name you would like given to the file, the second parameter must contain the file data as a string:

```php
$name = 'my_bio.txt';
$data = 'I was born in Istanbul ...';

$this->zip->addData($name, $data);
```

You are allowed multiple calls to this function in order to add several files to your archive. Example:

```php
$name = 'mydata1.txt';
$data = 'A Data String!';
$this->zip->addData($name, $data);

$name = 'mydata2.txt';
$data = 'Another Data String!';
$this->zip->addData($name, $data);
```

Or you can pass multiple files using an array:

```php
$data = array(
                'mydata1.txt' => 'A Data String!',
                'mydata2.txt' => 'Another Data String!'
            );

$this->zip->addData($data);

$this>zip->download('my_backup.zip'); 
```

If you would like your compressed data organized into sub-folders, include the path as part of the filename:

```php
$name = 'personal/my_bio.txt';
$data = 'I was born in Paris ...';

$this->zip->add_data($name, $data); 
```

The above example will place <dfn>my_bio.txt</dfn> inside a folder called <kbd>personal</kbd>.


#### $this->zip->addDir()

------

Permits you to add a directory. Usually this function is unnecessary since you can place your data into folders when using <dfn>$this->zip->add_data()</dfn>, but if you would like to create an empty folder you can do so. Example:

```php
$this->zip->addDir('myfolder'); // Creates a folder called "myfolder" 
```

#### $this->zip->readFile()

------

Permits you to compress a file that already exists somewhere on your server. Supply a file path and the zip class will read it and add it to the archive:

```php
$path = '/path/to/photo.jpg';

$this->zip->readFile($path);

// Download the file to your desktop. Name it "my_backup.zip"
$this->zip->download('my_backup.zip'); 
```

If you would like the Zip archive to maintain the directory structure of the file in it, pass <kbd>TRUE</kbd> (boolean) in the second parameter. Example:

```php
$path = '/path/to/photo.jpg';

$this->zip->readFile($path, TRUE);

// Download the file to your desktop. Name it "my_backup.zip"
$this->zip->download('my_backup.zip');
```

In the above example, photo.jpg will be placed inside two folders: <kbd>path/to/</kbd>

#### $this->zip->readDir()

------

Permits you to compress a folder (and its contents) that already exists somewhere on your server. Supply a file path to the directory and the zip class will recursively read it and recreate it as a Zip archive. All files contained within the supplied path will be encoded, as will any sub-folders contained within it. Example:

```php
$path = '/path/to/your/directory/';

$this->zip->readDir($path);

// Download the file to your desktop. Name it "my_backup.zip"
$this->zip->download('my_backup.zip'); 
```

#### $this->zip->archive()

------

Writes the Zip-encoded file to a directory on your server. Submit a valid server path ending in the file name. Make sure the directory is writable (666 or 777 is usually OK). Example:
```php
$this->zip->archive('/path/to/folder/myarchive.zip'); // Creates a file named myarchive.zip
```

#### $this->zip->download()

------

Causes the Zip file to be downloaded from your server. The function must be passed the name you would like the zip file called. Example:

```php
$this->zip->download('latest_stuff.zip'); // File will be named "latest_stuff.zip"
```

**Note:**  Do not display any data in the controller in which you call this function since it sends various server headers that cause the download to happen and the file to be treated as binary.

#### $this->zip->getZip()

------

Returns the Zip-compressed file data. Generally you will not need this function unless you want to do something unique with the data. Example:

```php
$name = 'my_bio.txt';
$data = 'I was born in an elevator...';

$this->zip->addData($name, $data);

$this->zipFile = $this->zip->getZip(); 
```

#### $this->zip->clearData()

------

The Zip class caches your zip data so that it doesn't need to recompile the Zip archive for each function you use above. If, however, you need to create multiple Zips, each with different data, you can clear the cache between calls. Example:

```php
$name = 'my_bio.txt';
$data = 'I was born in an elevator...';

$this->zip->addData($name, $data);
$zip_file = $this->zip->getZip();

$this->zip->clearData();

$name = 'photo.jpg';
$this->zip->readFile("/path/to/photo.jpg"); // Read the file's contents

$this->zip->download('myphotos.zip'); 
```