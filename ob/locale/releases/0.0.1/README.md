## Language Helper

------

The Language Helper provides functions to retrieve language files and lines of text for purposes of internationalization.

In your Obullo folder you'll find one called lang containing sets of language files. You can create your own language files as needed in order to display error and other messages in other languages.

Language files are typically stored in your obullo/lang directory. Alternately you can create a folder called <kbd>lang</kbd> inside your <kbd>app</kbd< folder and store them there.

**Note:** Each language should be stored in its own folder. For example, the English files are located at: <dfn>obullo/lang/english</dfn>

### Creating Language Files

------

Within the file you will assign each line of text to an array called <var>$lang</var> with this prototype:

```php
$lang['language_key'] = "The actual message to be shown";
```

**Note:** It's a good practice to use a common prefix for all messages in a given file to avoid collisions with similarly named items in other files. For example, if you are creating error messages you might prefix them with <var>error_</var>

```php
$lang['error_email_missing']    = "You must submit an email address";
$lang['error_url_missing']      = "You must submit a URL";
$lang['error_username_missing'] = "You must submit a username";
```

### Loading a Module Language File

------

If you want load language files from your <b>controllers</b> folder, you must create a folder called <b>lang</b>.

```php
- modules
        + welcome
        - blog
            + controllers
            + helpers
            - lang
               - english
                    menu.php
               - french
                    menu.php
               - korean
                    menu.php
            + models
            + views
```

This function load a <b>module</b> language file from your <dfn>modules/blog/lang/english</dfn> folder.

```php
new lang\start();('language', $folder= '');  // load module language file .. loader::lang('menu', 'english');
```

Where <samp>filename</samp> is the name of the file you wish to load (without the file extension), and language is the language set containing it (ie, english). If the second parameter is missing, the default language set in your <kbd>app/config/config.php</kbd> file will be used.

### Loading a Application Language File

------

If you want load language files from your <b>app</b> folder create your language files to there ..

```php
-  app
    + cache
    + config
    + errors
    + locale
        - english
            blog.php
            welcome.php
            contact.php 
```

This function load a <b>app</b> language file from your <dfn>app/locale</dfn> folder.

```php
new locale\start('app/filename', 'language');  // Load global language file.. 
```

```php
new locale\start('app/blog','spanish');
```

### Loading a Obullo Language File

------

If you want load language files from your <b>obullo</b> folder create your language files for base libraries.

```php
+  application
-  obullo
    + constants
    + database
    + helpers
    - locale
        - english
            calendar.php
            date.php
            .
            .
        - korean
            calendar.php
            date.php
            .
            .
```

This function load a <b>obullo</b> language file from your <dfn>base/locale</dfn> folder.

```php
new locale\start('ob/filename', 'language');  // Load base language file .. 
```

```php
loader::lang('ob/calendar'); // default english if you not provide second parameter 
```

```php
new locale\start('ob/calendar','spanish'); // load spanish file. 
```

### Fetching a Line of Text

------

Once your desired language file is loaded you can access any line of text using this function:

```php
locale('language_key');
```

You can use shotcut if its available ..

```php
lang('language_key');
```
Where <samp>language_key</samp> is the array key corresponding to the line you wish to show.

**Note:** This function simply returns the line. It does not echo it for you.

### Auto-loading Languages

------

If you find that you need a particular language globally throughout your application, you can tell Obullo [autoloader functions](/docs/advanced/#auto-loading-and-auto-running) it during system initialization. Like this ..

```php
$autoload['locale'] = array('locale1' => array('english'), 'locale2' => array('german', FALSE));
```

Also you can <b>partially</b> load a language file see the [globally auto-loading resources](#) section.