## Obullo URLs <a name="obullo-urls"></a>
By default, URLs in Obullo are designed to be search-engine and human friendly. Rather than using the standard "query string" approach to URLs that is synonymous with dynamic systems, Obullo uses a segment-based approach:

```php
example.com/{module}/news/article/my_article
```

### Removing the index.php file 

------

By default, the **index.php** file will be included in your URLs:

```php
example.com/index.php/module/news/article/my_article
```

### Apache HTTP Server <a name="apache-http-server"></a>

------

If you use Apache HTTP Server you can easily remove this file by using a **.htaccess** file with some simple rules. Here is an example of such a file, using the **"negative"** method in which everything is redirected except the specified items:

```php
# disable directory indexing for security
Options -Indexes
DirectoryIndex index.php

RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond $1 !^(index\.php|assets|robots\.txt)
# this is to remove the index.php in url
RewriteRule ^(.*)$ ./index.php/$1 [L,QSA]
```

In the above example, any HTTP request other than those for index.php, **/public** folder, and **robots.txt** is treated as a request for your index.php file.

**RewriteCond %{REQUEST_FILENAME} !-d**
If the request is for a real directory (one that exists on the server), index.php isn't served.

**RewriteCond %{REQUEST_FILENAME} !-f**
If the request is for a file that exists already on the server, index.php isn't served.

**RewriteRule ^(.*)$ /index.php**
All other requests are sent to index.php.

### Nginx HTTP Server <a name="nginx-http-server"></a>

------

Removing index.php file also easy in Nginx, add below the codes to your vhost file like this. Change the all **"/var/www/obullo.com/public"** texts as your web server root path.

```php

server {
        limit_conn   myzone  10;
        listen       80;
        server_name  obullo.com www.obullo.com;

        #charset utf-8;

        access_log  /var/www/obullo.com/log/host.access.log  main;
        error_log   /var/www/obullo.com/log/host.error.log;

        root   /var/www/obullo.com/public;
        index  index.php index.html index.htm;

        # START OBULLO FRAMEWORK REWRITE RULES
        # ( Obullo URI_PROTOCOL should be REQUEST_URI
        # / application /config / uri_protocol = REQUEST_URI )

        # enforce NO www
        if ($host ~* ^www\.(.*))
        {
                set $host_without_www $1;
                rewrite ^/(.*)$ $scheme://$host_without_www/$1 permanent;
        }

        # canonicalize Obullo url end points
        # if your default controller is something other than "welcome" you should change the following
        if ($request_uri ~* ^(/welcome(/index)?|/index(.php)?)/?$)
        {
                rewrite ^(.*)$ / permanent;
        }

        # removes trailing "index" from all controllers
        if ($request_uri ~* index/?$)
        {
                rewrite ^/(.*)/index/?$ /$1 permanent;
        }

        # removes trailing slashes (prevents SEO duplicate content issues)
        if (!-d $request_filename)
        {
                rewrite ^/(.+)/$ /$1 permanent;
        }

        # removes access to "obullo" folder, also allows a "System.php" controller
        if ($request_uri ~* ^/obullo)
        {
                rewrite ^/(.*)$ /index.php?/$1 last;
                break;
        }

        # unless the request is for a valid file (image, js, css, etc.), send to bootstrap
        if (!-e $request_filename)
        {
                rewrite ^/(.*)$ /index.php?/$1 last;
                break;
        }

        # END OBULLO FRAMEWORK REWRITE RULES

        # error_page  404              /404.html;
        # location = /404.html {
        #   root   /usr/share/nginx/html;
        # }

        # redirect server error pages to the static page /50x.html
        #
        # error_page   500 502 503 504  /50x.html;
        # location = /50x.html {
        #    root   /usr/share/nginx/html;
        # }

        # proxy the PHP scripts to Apache listening on 127.0.0.1:80
        #
        #location ~ \.php$ {
        #    proxy_pass   http://127.0.0.1;
        #}

        # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000

        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME /var/www/obullo.com/public/$fastcgi_script_name;
            include        fastcgi_params;
        }

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        location ~ /\.ht {
            deny  all;
        }
    }
```

**Edit your config.php**

Finally you should do some changes in your **app/config/config.php** file.

```php
$config['index_page'] = "";
$config['uri_protocol'] = "REQUEST_URI";   // QUERY_STRING
```

If you can't get uri requests try to change your uri protocol which is defined **app/config/config.php** file.

### Adding a URL Suffix <a name="adding-a-url-suffix"></a>

------

In your **config/config.php** file you can specify a suffix that will be added to all URLs generated by Obullo. For example, if a URL is this:

```php
example.com/index.php/shop/products/view/shoes
```

You can optionally add a suffix, like **.html**, making the page appear to be of a certain type:

```php
example.com/index.php/shop/products/view/shoes.html
```

### Enabling Query Strings <a name="enabling-query-strings"></a>

------

In some cases you might prefer to use query strings in URLs:

```php
index.php?d=shop&c=products&m=view&id=345
```

Obullo optionally supports this capability, which can be enabled in your application/config.php file. If you open your config file you'll see these items:

```php
$config['enable_query_strings'] = false;
$config['directory_trigger']  = 'd';
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
```


If you change "enable_query_strings" to true this feature will become active. Your controllers and functions will then be accessible using the "trigger" words you've set to invoke your directory, controllers and methods:

```php
index.php?d=directory&c=controller&m=method
```

**_Please note:_** If you are using query strings you will have to build your own URLs, rather than utilizing the URL helpers (and other helpers that generate URLs, like some of the form helpers) as these are designed to work with segment based URLs.

### URI Extensions <a name="uri-extensions"></a>

-------

You can use uri extensions when you use ajax, xml, rss, json.. requests, you can dynamically change the application behaviours using uri extensions. Also this functionality will help you to create friendly urls.

```php
example.com/module/class/post.json
```

You can define allowed extensions from your app/config/config.php file, default allowed URI extensions listed below.
- php
- html
- json
- xml
- raw
- rss
- ajax

Using URI Class $this->uri->getExtension(); function you can grab the called URI extension.

```php
switch($this->uri->getExtension())
{
    case 'json':
        echo json_encode($data);
    break;
    
    case 'html':
        echo $data;
    break;
}
```