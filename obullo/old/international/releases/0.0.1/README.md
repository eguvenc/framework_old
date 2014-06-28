## International Class

The Translator Class provides functions to retrieve language files and lines of text for purposes of internationalization.

In your app folder you'll find one called translator containing sets of language files. You can create your own language files as needed in order to display error and other messages in other languages.

Language files are typically stored in your <kbd>app/translations</kbd> directory.

**Note:** Each language should be stored in its own folder. For example, the English files are located at: <kbd>app/translator/english</kbd>

**Note:** This class is a component defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

### Using Sprintf()

<b>translate()</b> function automatically supports <b>sprintf</b> functionality so you <b>don't</b> need to use sprintf.

```php
echo translate('There are %d monkeys in the %s.',5,'tree');

// There are 5 monkeys in the tree.

```

Where <samp>language_key</samp> is the array key corresponding to the line you wish to show.

**Note:** This function simply returns the line. It does not echo it for you.

### Auto-loading Languages

------

If you find that you need a globally particular language throughout your application, you can tell Framework autoloader functions ( see the /docs/advanced/auto-loading ) it during system initialization.

```php
$this->translator->load('filename');
```