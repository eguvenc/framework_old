## Output Class

The Output class is a small class with one main function: To send the finalized web page to the requesting browser. It is also responsible for [caching](/docs/advanced/#caching-and-compression) your web pages, if you use that feature.

**Note:** This class is initialized automatically by the system so there is no need to do it manually.

Under normal circumstances you won't even notice the Output class since it works transparently without your intervention. For example, when you use the <dfn>new keyword</dfn> to load a view file, it's automatically passed to the Output class, which will be called automatically by Obullo at the end of system execution.

It is possible, however, for you to manually intervene with the output if you need to, using either of the two following functions:

#### $this->output->setOutput();

Permits you to manually set the final output string. Usage example:

```php
$this->output->setOutput($data);
```

**Important:** If you do set your output manually, it must be the last thing done in the function you call it from. For example, if you build a page in one of your controller functions, don't set the output until the end.

#### $this->output->getOutput();

Permits you to manually retrieve any output that has been sent for storage in the output class. Usage example:

```php
$string = $this->output->getOutput();
```

Note that data will only be retrievable from this function if it has been previously sent to the output class by one of the Obullo functions like <var>content_app_view()</var>.

#### $this->output->setHeader();

Permits you to manually set server headers, which the output class will send for you when outputting the final rendered display. Example:

```php
$this->output->setHeader("HTTP/1.0 200 OK");
$this->output->setHeader("HTTP/1.1 200 OK");
$this->output->setHeader('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
$this->output->setHeader("Cache-Control: no-store, no-cache, must-revalidate");
$this->output->setHeader("Cache-Control: post-check=0, pre-check=0");
$this->output->setHeader("Pragma: no-cache"); 
```

#### $this->output->setStatusHeader(code, 'text');

Permits you to manually set a server status header. Example:

```php
$this->output->setStatusHeader('401');  // Sets the header as:  Unauthorized
```

[See here]("http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html") for a full list of headers.

#### $this->output->cache();

The Obullo output library also controls caching. For more information, please see the [caching documentation](/docs/advanced/#caching-and-compression).

### Parsing Execution Variables

------

Obullo will parse the pseudo-variables <var>(elapsed_time)</var> and <var>(memory_usage)</var> in your output by default. To disable this, set the <var>$parse_exec_vars</var> class property to <var>FALSE</var> in your controller. 

```php
$this->output->parse_exec_vars = FALSE; 
```