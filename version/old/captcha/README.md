## Captcha Class

------

The Captcha class file contains functions that assist in creating CAPTCHA security images.

### Initializing the Class

------

```php
new Captcha();
$this->captcha->method();
```

Once loaded, the Captcha object will be available using: <dfn>$this->captcha->method()</dfn>

The following functions are available:

### Create a Captcha On the Fly

Creates captcha on the fly with default settings.

```php
<?php
new Captcha;
$this->captcha->create();
```
### Config File

You can set captcha configuration file from <kbd>app/config</kbd> folder.

##### Directory of Config File

```php
- app
- config
	captcha.php
```

### Expiration Time

The Captcha Class garbage collection function deletes images when expiration time is end.

```php
$captcha['expiration'] = 180; // seconds
```

### Test Mode

Before creating your captcha you can test all fonts and methods with the functions below.

```php
$this->captcha->fontTest();
```

This function quickly tests and produces html output using all <b>methods</b> for each fonts.


```php
$this->captcha->varTest();
```

This function quickly tests and produces html output using mostly used <b>variables</b>.

### Setting Driver

Captcha class has two types of driver : <kbd>secure</kbd> and <kbd>cool</kbd>. Default driver is <b>cool</b>. 

<b>Secure driver</b> produces images with random backgrounds. <b>Cool driver</b> is simple and it comes with white background.

```php
$this->captcha->setDriver('secure');
```

### Setting Font

```php
$this->captcha->setFont('Arial');
```
Multiple

```php
$this->captcha->setFont(array('Arial', 'Tahoma', 'Verdana'));
```

### Custom Font Directory

All captcha fonts are located in captcha package. But you can use your own fonts. Just load them into <kbd>assets/fonts</kbd> directory.

```php
- assets
	- fonts
		My_font1.ttf
		My_font2.ttf
		My_font3.ttf
```

###### Custom Font Example

```php
$myFonts = array(
                  'AlphaSmoke',         // Default captcha font
                  'Almontew',        
				  'My_Font1.ttf',		// Your custom fonts with extension (.ttf etc ..)
				  'My_Font2.ttf',
				  'My_Font3.ttf'
				 );

$this->captcha->setFont($myFonts);
```

### Exclude Fonts

If you want to remove unnecessary fonts from default configuration you can use exclude method.

```php
$this->captcha->excludeFont('AlphaSmoke');
```

Multiple:

```php
$this->captcha->excludeFont(array('AlphaSmoke','Anglican','Bknuckss'));
```

### Temp Directory

All captcha images stored in <b>data/temp/captcha</b> folder. You need give write permission to this folder.

When the expiration time ends, all images are deleted automatically. The expiration time is a configurable option in your <b>app/config/captcha.php</b> file.

```php
+ app
+ assets
- data
	- temp
		captcha
			8a57dc0c14f7b989c63d473dba00e380.gif
```

### Setting Color

Default color is cyan, you can set one or more colors. When you provide colors in array format it will produce random colors. 

You can set more colors from <b>app/config/captcha.php</b>.

<kbd>red</kbd> - <kbd>blue</kbd> - <kbd>green</kbd> - <kbd>black</kbd> - <kbd>yellow</kbd> 

```php
$this->captcha->setColor(array('red','black','cyan'));
```

### Setting Noise Color

Default color is cyan, you can set one or more colors. When you provide colors in array format it will produce random colors.

You can set more colors from <b>app/config/captcha.php</b>.

<kbd>red</kbd> - <kbd>blue</kbd> - <kbd>green</kbd> - <kbd>black</kbd> - <kbd>yellow</kbd> 

```php
$this->captcha->setNoiseColor(array('black','cyan'));
```

### Setting Height

When you set your <b>height</b> of image, image width is calculated <b>automatically</b> using your height, font size and character length values.

Default <kbd>40</kbd> px.

```php
$this->captcha->setHeight(40);
```

### Setting Font Size

Sets your font size of captcha code.

Default is <kbd>20</kbd> px.

```php
$this->captcha->setFontSize(20);
```

### Setting Wave

Sets a wave to your image for more strong captcha images.

Default is <kbd>true</kbd>

```php
$this->captcha->setWave(false);
```

### Setting Character Pool

Sets character varieties.

<table>
<thead>
<tr>
<th>Type</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td>numbers</td>
<td>23456789</td>
</tr>
<tr>
<td>alpha</td>
<td>ABCDEFGHJKLMNPRSTUVWXYZ</td>
</tr>
<tr>
<tr>
<td>random</td>
<td>23456789ABCDEFGHJKLMNPRSTUVWXYZ</td>
</tr>
</tbody>
</table>

We don't support these characters <kbd>"1 I 0 O"</kbd> because of the readability.

Default value is <kbd>random</kbd>.

Example:

```php
$this->captcha->setPool('numbers');
```

### Set Character Length

Sets the captcha code character length.

```php
$this->captcha->setChar(10);
```

### Clear

Resets all class variables to defaults.

```php
$this->captcha->clear();
```
### Geting Image Url

Produces image url for <b>img</b> tag.

```php
echo $this->captcha->getImageUrl();  // gives /data/temp/captcha/a6ef8fc84ed0eb687c8bd1558cc72a8e.gif
```
### Getting Image Id

Produces image id, it is necessary for validating the captcha answer.

```php
$this->captcha->getImageId();  // gives d9f0c551df608146444e5d514bc56777
```

### Complete Example

```
<?php
new Captcha();

$this->captcha->setDriver('secure');  // or set to "cool" with no background
$this->captcha->setPool('alpha');
$this->captcha->setChar(5);
$this->captcha->setFontSize(20);
$this->captcha->setHeight(36);
$this->captcha->setWave(false);
$this->captcha->setColor(array('red','black','blue'));
$this->captcha->setNoiseColor(array('red','black','blue'));
$this->captcha->setFont(array('AlphaSmoke','Bknuckss'));  // set font
$this->captcha->excludeFont(array('Bknuckss'));  // remove font
$this->captcha->create();
```
Put below the html in your view file.

```php
<form method="POST" action="/tutorials/hello_captcha/check">
    <img src="<?php echo $this->captcha->getImageUrl() ?>">
    <input type="hidden" value="<?php echo $this->captcha->getImageId() ?>" name="image_id">
    <input type="text" value="" name="answer">
    <input type="submit" value="Send" name="sendForm">
    <a href="/">Refresh</a>
</form>
```

In your controller you need to use the code below for checking the captcha answer.

```php
<?php
new Post;

$image_id = $this->post->get('image_id');
$code 	  = $this->sess->get($image_id);

if($this->post->get('answer') == $code)
{
	echo 'Captcha code is valid.';
}
```

### Function Reference

------

#### $this->captcha->setDriver(string);

Sets captcha driver ( <kbd>secure</kbd> or <kbd>cool</kbd> ), default is secure.

#### $this->captcha->setNoiseColor(string);

Sets image text background noise colors.

#### $this->captcha->setColor(string or array);

Sets image text color.

#### $this->captcha->setFont(string or array);

Sets image font.

#### $this->captcha->excludeFont(string or array);

Excludes font(s) from available fonts.

#### $this->captcha->setFontSize(integer);

Sets font size.

#### $this->captcha->setHeight(integer);

Sets image height.

#### $this->captcha->setPool(string);

Sets character pools.

#### $this->captcha->setChar(integer);

Sets the number of characters of image.

#### $this->captcha->setWave(true or false);

Starts and stops the text wave.

#### $this->captcha->setOutputHeader();

Whether to create captcha at browser header.

#### $this->captcha->create();

Starts creating a captcha element.

#### $this->captcha->getImageUrl();

Produces image url for <b>img</b> tag.

#### $this->captcha->getImageId();

Produces image id, it is necessary for validating the captcha answer.

#### $this->captcha->clear();

Returns to the default values​​.

#### $this->captcha->fontTest();

This function tests quickly and produces html output using all <b>methods</b> for each fonts.

#### $this->captcha->varTest();

This function tests quickly and produces html output using most used <b>variables</b>.