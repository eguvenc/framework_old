## Agent Class

------

The User Agent Class provides functions that help identify information about the browser, mobile device, or robot visiting your site. In addition you can get referrer information as well as language and supported character-set information.

### Initializing the Class

------

```php
new Agent();
$this->agent->method();
```

Once loaded, the Agent object will be available using: <dfn>$this->agent->method();</dfn>

### Grabbing the Instance

------

Also using new Agent(false); boolean you can grab the instance of Obullo libraries,"$this->agent->method()" will not available in the controller.

```php
$agent = new Agent(false);
$agent->method();
```

### User Agent Definitions

------

The user agent name definitions are located in a config file located at: <dfn>app/config/agents.php</dfn>. You may add items to the various user agent arrays if needed.

### Example

When the User Agent class is initialized it will attempt to determine whether the user agent browsing your site is a web browser, a mobile device, or a robot. It will also gather the platform information if it is available.

```php
new Agent();

if ($this->agent->isBrowser())
{
    $user_agent = $this->agent->browser().' '.$this->agent->version();
}
elseif ($this->agent->isRobot())
{
    $user_agent = $this->agent->robot();
}
elseif ($this->agent->isMobile())
{
    $user_agent = $this->agent->mobile();
}
else
{
    $user_agent = 'Unidentified User Agent';
}

echo $user_agent;

echo $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
```

### Function Reference

------

#### this->agent->isBrowser()

------

Returns TRUE/FALSE (boolean) if the user agent is a known web browser.

#### $this->agent->isMobile()

------

Returns TRUE/FALSE (boolean) if the user agent is a known mobile device.

#### 3$this->agent->isRobot()

------

Returns TRUE/FALSE (boolean) if the user agent is a known robot.

**Note:** The user agent library only contains the most common robot definitions. It is not a complete list of bots. There are hundreds of them so searching for each one would not be very efficient. If you find that some bots that commonly visit your site are missing from the list you can add them to your <dfn>app/config/user_agents.php</dfn> file.

#### $this->agent->isReferral()

------

Returns TRUE/FALSE (boolean) if the user agent was referred from another site.


#### $this->agent->browser()

------
Returns a string containing the name of the web browser viewing your site.

#### $this->agent->version()

-----
Returns a string containing the version number of the web browser viewing your site.

#### $this->agent->mobile()

-----

Returns a string containing the name of the mobile device viewing your site.

#### $this->agent->robot()

------
Returns a string containing the name of the robot viewing your site.


#### $this->agent->platform()

-----

Returns a string containing the platform viewing your site (Linux, Windows, OS X, etc.).

#### $this->agent->referrer()

------

The referrer, if the user agent was referred from another site. Typically you'll test for this as follows:

```php
if ($this->agent->isReferral())
{
    echo $this->agent->referrer();
}
```

#### $this->agent->agentString()

----

Returns a string containing the full user agent string. Typically it will be something like this:

```php
Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.4) Gecko/20060613 Camino/1.0.2
```

#### $this->agent->acceptLang()

----

Lets you determine if the user agent accepts a particular language. Example:

```php
if ($this->agent->acceptLang('en'))
{
    echo 'You accept English!';
}
```

**Note:** This function is not typically very reliable since some browsers do not provide language info, and even among those that do, it is not always accurate.

#### $this->agent->acceptCharset()

----

Lets you determine if the user agent accepts a particular character set. Example:

```php
if ($this->agent->acceptCharset('utf-8'))
{
    echo 'You browser supports UTF-8!';
}
```

**Note:** This function is not typically very reliable since some browsers do not provide character-set info, and even among those that do, it is not always accurate. 