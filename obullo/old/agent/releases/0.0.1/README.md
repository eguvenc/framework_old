## Agent Class

------

The User Agent Class provides functions that help identify information about the browser, mobile device, or robot visiting your site. In addition, you can get referrer information, language and supported character-set information.

### Initializing the Class

------

```php
new Agent();
$this->agent->method();
```

Once loaded, the Agent object will be available using: <dfn>$this->agent->method();</dfn>

### User Agent Definitions

------

The user agent name definitions are located in a config file on the directory <dfn>app/config/agents.php</dfn>. You may add items to the various user agent arrays if needed.

### Example

When the User Agent class is initialized, it will attempt to determine whether the user agent browsing your site is a web browser, a mobile device, or a robot. It will also gather the platform information if available.

```php
new Agent();

if ($this->agent->isBrowser())
{
    $user_agent = $this->agent->getBrowser().' '.$this->agent->getBrowserVersion();
}
elseif ($this->agent->isRobot())
{
    $user_agent = $this->agent->getRobotName();
}
elseif ($this->agent->isMobile())
{
    $user_agent = $this->agent->getMobileDevice();
}
else
{
    $user_agent = 'Unidentified User Agent';
}

echo $user_agent;

echo $this->agent->getPlatform(); // Platform info (Windows, Linux, Mac, etc.)
```

### Function Reference

------

#### this->agent->isBrowser()

------

Returns true/false (boolean) if the user agent is a known web browser.

#### $this->agent->isMobile()

------

Returns true/false (boolean) if the user agent is a known mobile device.

#### $this->agent->isRobot()

------

Returns true/false (boolean) if the user agent is a known robot.

**Note:** The user agent library only contains the most common robot definitions. It is not a complete list of bots. There are hundreds of them so searching for each one would not be very efficient. If you find that some bots that commonly visit your site are missing from the list you can add them to your <dfn>app/config/user_agents.php</dfn> file.

#### $this->agent->isReferral()

------

Returns true/false (boolean) if the user agent is referred from another site.


#### $this->agent->getBrowser()

------
Returns a string containing the name of the web browser viewing your site.

#### $this->agent->getBrowserVersion()

-----
Returns a string containing the version number of the web browser viewing your site.

#### $this->agent->getMobileDevice()

-----

Returns a string containing the name of the mobile device viewing your site.

#### $this->agent->getRobotName()

------
Returns a string containing the name of the robot viewing your site.


#### $this->agent->getPlatform()

-----

Returns a string containing the platform viewing your site (Linux, Windows, OS X, etc.).

#### $this->agent->getReferrer()

------

The referrer, if the user agent is referred from another site. Typically you'll test for this as follows:

```php
if ($this->agent->isReferral())
{
    echo $this->agent->getReferrer();
}
```

#### $this->agent->getAgentString()

----

Returns a string containing the full user agent string. Typically it will be something like this:

```php
Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.4) Gecko/20060613 Camino/1.0.2
```

#### $this->agent->getAcceptLang()

----

Lets you determine if the user agent accepts a particular language. Example:

```php
if ($this->agent->getAcceptLang('en'))
{
    echo 'You accept English !';
}
```

**Note:** This function is not typically very reliable since some browsers do not provide language info, and even among those that do, it is not always accurate.

#### $this->agent->getAcceptCharset()

----

Lets you determine if the user agent accepts a particular character set. Example:

```php
if ($this->agent->getAcceptCharset('utf-8'))
{
    echo 'You browser supports UTF-8';
}
```

**Note:** This function is not typically very reliable since some browsers do not provide character-set info, and even among those that do, it is not always accurate. 