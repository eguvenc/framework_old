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