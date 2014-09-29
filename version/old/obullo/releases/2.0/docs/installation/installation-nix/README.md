## Installation *-nix / Mac Os <a name="installation-nix"></a>


### Downloading the Obm Executable

------

#### Locally

To actually get <kbd>Obm</kbd> (Obullo Manager), we need to do two things. The first one is installing Obm (again, this means downloading it into your project):

First of all you need to create your project folder.

```php
cd /var/www
mkdir myproject
```
Now you can install the <b>Obm</b>.

```php
wget https://obullo.com/installer
```

This will just download the <kbd>obm</kbd> file to your working directory. This file is a native PHP file which can be run on the command line.

After downloading you can run the obm.

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

To update your packages run the <b>obm update</b>.

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

The <b>asterisks ( * )</b> mean getting the <kbd>latest version</kbd> of the package. If you need a previous stable version remove the asterisk ( * ) and set it to a specific number. ( e.g. auth: "0.0.2" ).

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
if available, you can replace the core components with third-party packages from the [obm repository]( http://obm.obullo.com ). Below the example we set the <b>log</b> component to <b>Thirdparty_Logwrite</b> package.

```php
{
   "dependencies": {
        "log": "*",
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