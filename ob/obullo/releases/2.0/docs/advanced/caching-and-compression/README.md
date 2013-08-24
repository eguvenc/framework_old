## Caching and Compression<a name="caching-compression"></a>

### Web Page Caching

------

Obullo lets you cache your pages in order to achieve maximum performance.

Although Obullo is quite fast, the amount of dynamic information you display in your pages will correlate directly to the server resources, memory, and processing cycles utilized, which affect your page load speeds. By caching your pages, since they are saved in their fully rendered state, you can achieve performance that nears that of static web pages.

### How Does Caching Work?

------

Caching can be enabled on a per-page basis, and you can set the length of time that a page should remain cached before being refreshed. When a page is loaded for the first time, the cache file will be written to your app/cache folder. On subsequent page loads the cache file will be retrieved and sent to the requesting user's browser. If it has expired, it will be deleted and refreshed before being sent to the browser.

Note: The Benchmark tag is not cached so you can still view your page load speed when caching is enabled.

### Enabling Caching

------

To enable caching, put the following tag in any of your controller functions:

```php
$this->output->cache(n);
```

Where <var>n</var> is the number of <b>minutes</b> you wish the page to remain cached between refreshes.

The above tag can go anywhere within a function. It is not affected by the order that it appears, so place it wherever it seems most logical to you. Once the tag is in place, your pages will begin being cached.

**Warning:** Because of the way Obullo stores content for output, caching will only work if you are generating display for your controller with a [view](/ob/obullo/releases/2.0/docs/general/views).

**Note:** Before the cache files can be written you must set the file permissions on your <dfn>app/cache</dfn> folder such that it is writable.

### Deleting Caches

------

If you no longer wish to cache a file you can remove the caching tag and it will no longer be refreshed when it expires.

**Note:** Removing the tag will not delete the cache immediately. It will have to expire normally. If you need to remove it earlier you will need to manually delete it from your cache folder.

### Gzip Compression

------

Gzip enables output compression for faster page loads. When enabled, the output class will test whether your server supports Gzip. Even if it does, however, not all browsers support compression so enable only if you are reasonably sure your visitors can handle it.

To enable gzip compression go <dfn>app/config/cache.php</dfn> and change compress_ouput value. 

```php
$cache['compress_output']       = TRUE;  // Compress switch
$cache['compression_level']     = 8;     // Compress level
```

### How Can I Test the Page is Compressed ?

------

There are three way to make sure you are actually serving up compressed content.
<ol>
    <li>View the headers: Use <a href="https://addons.mozilla.org/en-US/firefox/addon/live-http-headers/">Live HTTP Headers</a> to examine the response. Look for a line that says "Content-encoding: gzip".</li>
   <li>Firefox browser: Use Web Developer Toolbar > Information > View Document Size to see whether the page is compressed.</li>
    <li>Online: Use the <a href="http://www.gidnetwork.com/tools/gzip-test.php">online gzip test</a> to check whether your page is compressed.</li></ol>

**Note:** Above the topics related about online test not for the localhost.

If you are getting a blank page when compression is enabled it means you are prematurely outputting something to your browser. It could even be a line of whitespace at the end of one of your scripts. For compression to work, nothing can be sent before the output buffer is called by the output class.

**Critical:** Do not *"echo"* any values with compression enabled this may cause server crashes.
