## Url Class

The URL Class file contains functions that assist in working with URLs.

### Initializing a Session Class

------

```php
new Url;
$this->url->method();
```

The following functions are available:

#### $this->url->anchor($uri = '', $title = '', $attributes = '', $suffix = true)

Creates a standard HTML anchor link based on your local site URL:

```php
<a href="http://example.com">Click Here</a>
```

The tag has four optional parameters:

```php
$this->url->anchor(uri segments, text, attributes, $suffix)
```

The first parameter can contain any segments you wish to append to the URL. As with the url\site() function above, segments can be a string or an array.

**Note:** If you are building links that are internal to your application do not include the base URL (http://...). This will be added automatically from the information specified in your config file. Include only the URI segments you wish.

The second segment is the text you would like the link to say. If you leave it blank, the URL will be used.

The third parameter can contain a list of attributes you would like to add to the link. The attributes can be a simple string or an associative array.

If you set url suffix (like .html) in config.php using fourth parameter **$suffix = false** you can switch off suffix for current site url.

Here are some examples:

```php
echo $this->url->anchor('news/local/123', 'title="My News"');
```

Would produce: <a href="news/local/123" title="My News">My News</a>

```php
echo $this->url->anchor('news/local/123', 'My News', array('title' => 'The best news!'));
```

Would produce: <a href="news/local/123" title="The best news!">My News</a>

Url anchor function support **'#'** characters. You can use it like this ..

```php
echo $this->url->anchor('news/local/123#sharp_url');
```

#### $this->url->anchorPopup()

Nearly identical to the anchor() function except that it opens the URL in a new window. You can specify JavaScript window attributes in the third parameter to control how the window is opened. If the third parameter is not set it will simply open a new window with your own browser settings. Here is an example with attributes:

```php
$atts = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    => '0',
              'screeny'    => '0'
            );

echo $this->url->anchorPopup('news/local/index/123', 'Click Me!', $atts);
```

**Note:** The above attributes are the function defaults so you only need to set the ones that are different from what you need. If you want the function to use all of its defaults simply pass an empty array in the third parameter:

```php
echo $this->url->anchorPopup('news/local/index/123', 'Click Me!', array());
```

#### $this->url->createTitle()

Takes a string as input and creates a human-friendly URL string. This is useful if, for example, you have a blog in which you'd like to use the title of your entries in the URL. Example:

```php
echo $this->url->createTitle("What's wrong with CSS?", $title);

// Produces: Whats-wrong-with-CSS
```

The second parameter determines the word delimiter. By default dashes are used. Options are: dash, or underscore:

```php
echo $this->url->createTitle("What's wrong with CSS?", 'underscore');

// Produces: Whats_wrong_with_CSS
```

The third parameter determines whether or not lowercase characters are forced. By default they are not. Options are boolean true/false:

```php
echo $this->url->createTitle("What's wrong with CSS?", 'underscore', true);

// Produces: whats_wrong_with_css
```

#### $this->url->prep()

This function will add http:// in the event it is missing from a URL. Pass the URL string to the function like this:

```php
$url = $this->url->prep("example.com");
```

#### $this->url->redirect($uri = '', $method = 'location', $http_response_code = '302', $suffix = true)

Does a "header redirect" to the local URI specified. Just like other functions in this helper, this one is designed to redirect to a local URL within your site. You will not specify the full site URL, but rather simply the URI segments to the controller you want to direct to. The function will build the URL based on your config file values.

The optional second parameter allows you to choose the "location" method (default) or the "refresh" method. Location is faster, but on Windows servers it can sometimes be a problem. The optional third parameter allows you to send a specific HTTP Response Code - this could be used for example to create 301 redirects for search engine purposes. The default Response Code is 302. The third parameter is only available with 'location' redirects, and not 'refresh'. Examples:

```php
if ($loggedin == false)
{
	$this->url->redirect('login/form', 'refresh[0]');
}

// with 301 redirect
$this->url->redirect('news/article/13', 'location', 301);
```

If you set URL suffix (like .html) in config.php using fourth parameter $suffix = false you can switch off suffix for current site url.

**Note:** In order for this function to work it must be used before anything is outputted to the browser since it utilizes server headers.

**Note:** For very fine grained control over headers, you should use the Output Class <kbd>/docs/packages/output</kbd>'s setHeader() function.

If you use refresh parameter you can set refresh time.

```php
$this->url->redirect('/payments/response_status', 'refresh[4]');  // output  header("Refresh:4;url=/payments/response_status");
```