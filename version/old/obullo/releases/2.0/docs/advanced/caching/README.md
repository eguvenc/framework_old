## Caching <a name="caching"></a>

### Web Page Caching

------

Framework lets you cache your pages in order to achieve maximum performance.

Although framework is quite fast, the amount of dynamic information you display in your pages will correlate directly to the server resources, memory, and processing cycles utilized, which affects your page load speeds. By caching your pages, since they are saved in their fully rendered state, you can achieve performance that nears that of static web pages.

### How Does Caching Work?

------

Caching can be enabled on a per-page basis, and you can set the length of time that a page should remain cached before being refreshed. When a page is loaded for the first time, the cache file will be written to your app/cache folder. On subsequent page loads the cache file will be retrieved and sent to the requesting user's browser. If it has expired, it will be deleted and refreshed before being sent to the browser.

**Note:** The Benchmark tag is not cached so you can still view your page load speed when caching is enabled.

### Enabling Caching

------

To enable caching, put the following tag in any of your controller functions:

```php
$this->output->setCache(n);
```

Where <b>n</b> is the number of <b>minutes</b> you wish the page to remain cached between refreshes.

The above tag can go anywhere within a function. It is not affected by the order that it appears, so place it wherever it seems most logical to you. Once the tag is in place, your pages will begin being cached.

**Warning:** Because of the way framework stores content for output, caching will only work if you are generating display for your controller with a view.

**Note:** Before the cache files can be written you must set the file permissions on your <kbd>app/cache</kbd> folder such that it is writable.

### Deleting Caches

------

If you no longer wish to cache a file you can remove the caching tag and it will no longer be refreshed when it expires.

**Note:** Removing the tag will not delete the cache immediately. It will have to expire normally. If you need to remove it <b>earlier</b> you will need to <b>manually</b> delete it from your cache folder.