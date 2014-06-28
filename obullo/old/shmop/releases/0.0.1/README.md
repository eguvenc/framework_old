## Shmod Class ( Shared Memory )

------

Allows to store datasets directly in *nix shared memory with PHP.

A little information about shared memory: http://www.ibm.com/developerworks/opensource/library/os-php-shared-memory/index.html

### Initializing the Class

------

```php
new Shmop;
$this->shmop->method();
```

Once loaded, the Shmop object will be available using: <dfn>$this->shmop->method()</dfn>

The following functions are available:

#### $this->shmop->set($key, $data, $charset = null);

Open a memory segment and write the data to *unix shared memory.

```php
new Shmop;

$this->shmop->set('test', 123);
```

#### $this->shmop->get($key);

Read from shared memory, if segment key exists returns to "string" otherwise it returns to "null".

```php
new Shmop;

if($this->shmop->get('test') != null)
{
    $this->shmop->set('test', 123);
}
```

#### $this->shmop->delete($key);

Delete the memory segment from *unix shared memory.


```php
$this->shmop->delete('test');
```