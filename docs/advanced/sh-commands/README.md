## Sh Commands<a name="sh-commands"></a>

If you work under the *nix operating systems, we have useful sh commands.

#### Clear.sh ( Clear logs and cache files )

When you move your project to another server you need to clear all log files and caches. Go your terminal and type your project path and run the clear.sh

```php
root@localhost:/var/www/framework$ sh clear.sh // Great, temporary files clear job done !
```

#### Export.sh ( Export project files )

When you upload project files to your live server you need export it. Export command remove all .svn and .git files and save the project to export folder.

```php
root@localhost:/var/www/framework$ sh export.sh  // Export process named as export_2012-11-12_13-19 and completed !
```