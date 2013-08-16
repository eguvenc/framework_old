
## Hmvc Class

------

Using Obullo's simple hmvc library you can execute hmvc requests between your modules, the HMVC technology offers more flexibility. About HMVC structure you can find more information in [Advanced Topics / HMVC section](/docs/database/).

### Initializing the Class

------

Unlike other classes we can call the HMVC library inside from request() function using [request helper](http://obullo.com/user_guide/en/1.0.1/request-helper.html). $request = request('module/controller/method', FALSE);

Once loaded, the Calendar object will be available using: $request->method();
Example Hmvc Request

Here is a very simple example showing how you can call a hmvc request using HMVC object methods.
$request = request('/module/controller/method/arguments', FALSE);

$request->set_method('get', $params = array());
echo $request->exec();
Ouick Access

Normally first parameter assigned for request method but if you not choose a method , Obullo request helper will do atuomatically $_GET request don't forget Obullo also store get and post data into $_REQUEST global variable.
echo request('blog/blog/read')->exec(); Quick Decoding JSON Format $row = request('module/controller/method')->decode('json')->exec();

echo $row->key; // output value
HMVC Requests in Sub Modules

If you want to call a HMVC request in the sub.module, you need to provide sub.modulename otherwise Obullo will call a request outside of your sub.module folder.
echo request('sub.module/module/controller/method')->exec();
Function Reference
$request->set_method($method = 'get', $params = 'mixed');

Set the hmvc request method.
Available Query Methods

    POST
    GET
    UPDATE
    DELETE
    PUT ( When we use PUT method we provide data as string using third parameter instead of array. )

$request->cache($time = 0 int);

You can do cache for your static hmvc requests. When a hmvc request called the first time, the cache file will be written to your application/core/cache folder. You can learn more details about ouput caching.
$request->set_server($key = '', $val = '');

Set the $_SERVER headers for current hmvc scope.
$request->no_loop($turn_on = true boolean);
Some users some times use the HMVC requests in the parent controllers in this case normally a HMVC library do a unlimited loop and this may cause server crashes, beware if you use hmvc requests in parent controllers you have to use no_loop(); method for each requests.
$request->exec();

Execute hmvc call and return to response.
$request->decode($format = 'json')->exec();

Before the execute of the results you can decode response in JSON format.