## Compression <a name="compression"></a>

### Gzip Compression

------

Gzip enables output compression for faster page loads. When enabled, the output class will test whether your server supports Gzip. Even if it does, however, not all browsers support compression so enable only if you are reasonably sure your visitors can handle it.

To enable gzip compression go <kbd>app/config/config.php</kbd> and set <b>compress_output</b> value to true. 

```php
$config['compress_output']       = true;
```

### How Can I Test the Page is Compressed ?

------

There are <b>three way</b>s to make sure you are actually serving up compressed content.
<ol>
    <li><b>View the headers:</b> Use [Live HTTP Headers](https://addons.mozilla.org/en-US/firefox/addon/live-http-headers/) to examine the response. Look for a line that says <b>"Content-encoding: gzip"</b>.</li>
    <li><b>Firefox browser:</b> Use <kbd>Web Developer Toolbar > Information > View Document Size</kbd> to see whether the page is compressed.</li>
    <li><b>Online:</b> Use the [online gzip test](http://www.gidnetwork.com/tools/gzip-test.php) to check whether your page is compressed.</li>
</ol>

**Note:** Above the topics related about online test not for the localhost.

If you are getting a blank page when compression is enabled it means you are prematurely outputting something to your browser. It could even be a line of whitespace at the end of one of your scripts. For compression to work, nothing can be sent before the output buffer is called by the output class.

**Critical:** Do not *"echo"* any values when compression enabled, this may cause server crashes.
