## Alternate PHP Syntax for View Files <a name="alternate-php-syntax"></a>

### Alternate PHP Syntax for View Files

------

If you do not utilize Obullo's template engine, you'll be using pure PHP in your View files. To minimize the PHP code in these files, and to make it easier to identify the code blocks it is recommended that you use PHPs alternative syntax for control structures and short tag echo statements. If you are not familiar with this syntax, it allows you to eliminate the braces from your code, and eliminate "echo" statements.

### Short Tag Support

------

**Note:** Obullo does not support php short tags at this time. You can open *'short_tag_open'* value from your php.ini file.If you use shared hosting don't use short tags for now.

**Important:** If your server does not support *short_tag_open* functionality then all your php codes will shown as string which is used short tags.

### Alternative Echos

------

Normally to echo, or print out a variable you would do this:

```php
<?php echo $variable; ?>
```

With the alternative syntax you can instead do it this way:

```php
<?=$variable?>
```

### Alternative Control Structures

------

Controls structures, like <var>if</var>, <var>for</var>, <var>foreach</var>, and <var>while</var> can be written in a simplified format as well. Here is an example using foreach:

```php
<ul>

<?php foreach($todo as $item): ?>

<li><?=$item?></li>

<?php endforeach; ?>

</ul>
```

Notice that there are no braces. Instead, the end brace is replaced with endforeach. Each of the control structures listed above has a similar closing syntax: <var>endif</var>, <var>endfor</var>, <var>endforeach</var>, and <var>endwhile</var>

Also notice that instead of using a semicolon after each structure (except the last one), there is a colon. This is important!


Here is another example, using if/elseif/else. Notice the colons:

```php
<?php if ($username == 'sally'): ?>

   <h3>Hi Sally</h3>

<?php elseif ($username == 'joe'): ?>

   <h3>Hi Joe</h3>

<?php else: ?>

   <h3>Hi unknown user</h3>

<?php endif; ?>
```