
## Framework Todo List

------

* Controller names configuration option from config, keep flexibility for typing /HelloWorld or /hello_world
* Create service provider connection manager for mailer and cache libraries.
* Mailer service from() method should be called in the service.
 
* Add when() function to router class then we can filter post|get headers.
* Cache libraries requires clear documentation.
* Captcha library requires clear documentation.
* Form Element library requires clear documentation.
* Security class csrf documentation and csrf tests.

* Research: Multi thread in more filexible way http://stackoverflow.com/questions/70855/how-can-one-use-multi-threading-in-php-applications
* X-Frame options: https://github.com/django/django/blob/master/django/middleware/clickjacking.py


* ----------- Testing----------------
*
* Create a domain testsuite.obullo.com

    Planned tests:
        * Config
        * Log
        * Router
            - routes
            - sub domain routing
            - filters
            - maintenance filter
            - maintenance filter with sub domains
        * Queue
        * Permissions
        * Validator
        * Auth
        * Form
        * Mail
        * Session
            - set
            - get
            - session cookie domain share test ( sub domains)
            - regenerate id
            - destroy
        * Tree
        * Translation
        * Url
        * Cookie
