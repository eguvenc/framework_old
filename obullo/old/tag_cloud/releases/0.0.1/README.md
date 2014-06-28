## Tag Cloud Class
Tag Cloud returns <kbd>Array</kbd> and <kbd>Html</kbd>. 


### Array
Generated tags are returned as arrays so that you can use them in the way you want.


##### Creating Tag Cloud
The simplest way to create a tag.
```php
new Tag_Cloud();
$this->tag_cloud->addTag('tag test');
$rendered = $this->tag_cloud->render();	// This function is triggering functions required to create tags.
print_r($rendered); 					// Output will be as follows.
/*
Array
(
    [0] => Array
        (
            [tag] => tag test
            [url] => tag_test
            [attribute] => 
        )
)
 */
```
#### Auto Url
 The seconda part of the <kbd>addTag()</kbd> function is url field. An example of triggering Auto url:
```php
$this->tag_cloud->addTag('tag name');
// OR
$this->tag_cloud->addTag('tag name','');
//OR
$this->tag_cloud->addTag('tag name',false);
/**
 * Outcomes in this way will be.
Array
(
    [0] => Array
        (
            [tag] => tag name
            [url] => tag_name
            [attribute] =>
        )
)
 */
```
If you want to use your own url, you can enter your url after the tag.

Example:
```php
$this->tag_cloud->addTag('tag test', 'my-url-tag-test');
/**
 * Outcomes in this way will be.
Array
(
    [0] => Array
        (
            [tag] => tag test
            [url] => my-url-tag-test
            [attribute] => 
        )
)
 */
```
Auto URL Settings can be configured in the config file.
###### Directory of Config File
```php
- app
	- config
		tag_cloud.php
```
Url config
```php
$tag_cloud['seo_segment'] = 'tag'; // Seo segment name e.g  http://www.domain.com/tag/test_tag_name
$tag_cloud['formatting']  = array(
								'transformation' => function($str){
									return mb_strtolower($str);
								},
								'url_separator'  => '_',	  // tag separator e.g. test_tag_name
								'link_seperator' => '&nbsp;', // link seperator
															  // e.g. <a></a>&nbsp;<a></a>
								);
```
#### Attribute
The third part of the <kbd>addTag()</kbd> function is the attribute field. You can put attributes here as you want.

Example
```php
$this->tag_cloud->addTag('tag test', 'my-url-tag-test', 'class="tags" id="#test"');
```
If you want to add attributes while using the Auto URL Structure, you should absolutely add <kbd>''</kbd> or <kbd>false</kbd> to  the URL field. Otherweise, The attribute you add will return as if it is in the URL field.
```php
$this->tag_cloud->addTag('tag test', '', 'class="tags" id="#test"');
// OR
$this->tag_cloud->addTag('tag test', false, 'class="tags" id="#test"');
```
#### Multi Tag
 To create a multi tag, just run the function <kbd>addTag()</kbd>

Example:
```php
$this->tag_cloud->addTag('tag test', 'tag_test');
$this->tag_cloud->addTag('tag test 2', '', 'class="tags"'); // Auto URL because the second field is empty
$rendered = $this->tag_cloud->render();
print_r($rendered);
/*
Array
(
    [0] => Array
        (
            [tag] => tag test
            [url] => tag_test
            [attribute] =>
        )
    [1] => Array
        (
            [tag] => tag test 2
            [url] => tag_test_2
            [attribute] => class="tags"
        )
)
 */
```
### Html
##### Creating Tag Cloud
The simplest way to create a tag.
```php
new Tag_Cloud();
$this->tag_cloud->addTag('tag test');
$rendered = $this->tag_cloud->render('html');
print_r($rendered); // Output will be as follows.
/*
	<a href="/tag/tag_test">tag test</a>
 */
```
#### Multi Tag
```php
new Tag_Cloud();
$this->tag_cloud->addTag('tag test 1');
$this->tag_cloud->addTag('tag test 2');
$this->tag_cloud->addTag('tag test 3');
$rendered = $this->tag_cloud->render('html');
print_r($rendered); // Output will be as follows.
/*
	<a href="/index.php/tag/tag_test_1">tag test 1</a>&nbsp;
	<a href="/index.php/tag/tag_test_2">tag test 2</a>&nbsp;
	<a href="/index.php/tag/tag_test_3">tag test 3</a>
 */
```
Tags will be shuffled at every refresh.
#### Shuffle
By default if returns <kbd>true</kbd>. To close the shuffle, you need to enter false to the <kbd>render()</kbd>.
```php
$this->tag_cloud->render('html',false);  // default true
```
#### Color
Automatically color the tags. There are three color types: <kbd>dark, light and mixed</kbd>.
```php
$this->tag_cloud->setColor('dark');
/*
	<a href="/tag/tag_test"  style="color: rgb(234,15,22)">tag test</a>
 */
```
#### Complete Example

```php
$this->tag_cloud->setColor('light');
$this->tag_cloud->addTag('tag test 1','','class="tags1" id="#test"');
$this->tag_cloud->addTag('tag test 2','','class="tags2" id="#test"');
$this->tag_cloud->addTag('tag test 3','','class="tags3" id="#test"');
$this->tag_cloud->addTag('tag test 4','','class="tags4" id="#test"');
$rendered = $this->render('html',false); // Shuffle false dönüşler random olmayacaktır.
print_r($rendered); // Output will be as follows.
/*
	<a href="/tag/tag_1" class="tags1" id="#test" style="color: rgb(29,248,177)">tag 1</a>&nbsp;
	<a href="/tag/tag_2" class="tags2" id="#test" style="color: rgb(176,173,66)">tag 2</a>&nbsp;
	<a href="/tag/tag_3" class="tags3" id="#test" style="color: rgb(115,113,19)">tag 3</a>&nbsp;
	<a href="/tag/tag_4" class="tags4" id="#test" style="color: rgb(23,246,112)">tag 4</a>
 */
```

### Function Reference

---

#### $this->tag_cloud->addTag($tag_name, $tag_url, $attribute);

Adds Tag

#### $this->tag_cloud->setColor($color_type);

Sets Color

#### $this->tag_cloud->render($type, true OR false);

Renders