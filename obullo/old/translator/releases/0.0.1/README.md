## Translator Class

The Translator Class provides functions to retrieve language files and lines of text for purposes of internationalization.

In your app folder you'll find one called translator containing sets of language files. You can create your own language files as needed in order to display error and other messages in other languages.

Language files are typically stored in your <kbd>app/translator</kbd> directory.

**Note:** Each language should be stored in its own folder. For example, the English files are located at: <kbd>app/translations/en_US</kbd>

**Note:** This class is a component defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

### Creating Language Files

------

Within the file you will assign each line of text to an array called <var>$translate</var> with this prototype:

```php
$translate['language_key'] = "The actual message to be shown";
```

**Note:** It's a good practice to use actual message as key for all messages in a given file to avoid collisions with similarly named items in other files. 

```php
$translate['You must submit an email address'] = "You must submit an email address";
$translate['You must submit a URL']  = "You must submit a URL";
$translate['You must submit a username'] = "You must submit a username";
```

```php
$this->translator->load('language', $folder= '');  // load translator file
```

Where <samp>filename</samp> is the name of the file you wish to load (without the file extension), and language is the language set containing it (ie, english). If the second parameter is missing, the default language set in your <kbd>app/config/config.php</kbd> file will be used.

### Loading a Translate File

------

If you want load language files from your <b>app</b> folder create your language files to there ..

```php
-  app
    + cache
    + config
    + templates
    - translations
        - en_US
            blog.php
            welcome.php
            contact.php 
```

This function loads a language file from your <kbd>app/translator</kbd> folder.

```php
new Cookie;

$this->translator->load('welcome', $this->cookie->get('lang'));

translate('Welcome to the our site !');
```

For Example :

```php
$this->translator->load('calendar','spanish');
```

### Loading the Framework Language File

Some of the packages use framework language file which is located in your <kbd>app/translations</kbd> folder. You can change the default language. ( look at <kbd>app/config/debug/config.php</kbd> ) 

Core packages will load framework language files which are located in <kbd>app/translations/$language</kbd> folder.

------

```php
-  app
    +config
    +errors
    -translations
        -en_US
            date_get.php
            ftp.php
            email.php
            odm.php
            validator.php
            welcome.php
            ...
        -es_ES
            date_get.php
            ftp.php
            email.php
            odm.php
            validator.php
            ...
```

This function loads the <b>welcome</b> language file from your <kbd>app/translator/es_ES</kbd> folder.

```php
$this->translator->load('welcome', 'es_ES'); 
```

```php
$this->translator->load('welcome'); // default "en_US" if you not provide second parameter 
```

### Fetching a Line of Text

------

Once your desired language file is loaded you can access any line of text using this function:

```php
translate('language_key');
```
translate() function returns the translated line if language line exists in your file, otherwise it returns to default text that you are provide.

### Checking a Line of Text

hasTranslate('language_key');

hasTranslate() function returns to false if language line exists in your file otherwise true.

### Using Sprintf()

<b>translate()</b> function automatically supports <b>sprintf</b> functionality so you <b>don't</b> need to use sprintf.

```php
echo translate('There are %d monkeys in the %s.', 5, 'tree');

// There are 5 monkeys in the tree.

```

Where <samp>language_key</samp> is the array key corresponding to the line you wish to show.

### Auto-loading Languages

------

If you find that you need a globally particular language throughout your application, you can tell Framework autoloader functions ( see the /docs/advanced/auto-loading ) it during system initialization.

```php
$this->translator->load('filename');
```
