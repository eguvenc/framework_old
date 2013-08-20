## Benchmarking Helper

------

Obullo has a Benchmarking helper that is always active, enabling the time difference between any two marked points to be calculated.

**Note:** This helper is initialized automatically by the system so there is no need to do it manually.

In addition, the benchmark is always started the moment the framework is invoked, and ended by the output class right before sending the final view to the browser, enabling a very accurate timing of the entire system execution to be shown.

#### Table of Contents

<ul>
    <li><a href="using-the-benchmark-helper">Using the Benchmark Helper</a></li>
    <li><a href="profiling-your-benchmark">Profiling Your Benchmark Points</a></li>
    <li><a href="displaying-total-execution-time">Displaying Total Execution Time</a></li>
    <li><a href="displaying-memory-consumption">Displaying Memory Consumption</a></li>
</ul>

### Using the Benchmark Helper <a name="using-the-benchmark-helper"></a>

------

The Benchmark helper can be used within your controllers, views, or your Models. The process for usage is this:

<ol>
   <li>Mark a start point</li>
   <li>Mark an end point</li>
   <li>Run the "elapsed time" function to view the results</li>
</ol>

Here's an example using real code:

```php
bench\mark('code_start');

// Some code happens here

bench\mark('code_end');

echo bench\elapsedTime('code_start', 'code_end');
```

**Note:** The words "code_start" and "code_end" are arbitrary. They are simply words used to set two markers. You can use any words you want, and you can set multiple sets of markers. Consider this example:

```php
bench\mark('dog');

// Some code happens here

bench\mark('cat');

// More code happens here

bench\mark('bird');

echo bench\elapsedTime('dog', 'cat');
echo bench\elapsedTime('cat', 'bird');
echo bench\elapsedTime('dog', 'bird');

### Profiling Your Benchmark Points <a name="profiling-your-benchmark-points"></a>

------

If you want your benchmark data to be available to the [Profiler](/docs/advanced/#profiling-your-application) all of your marked points must be set up in pairs, and each mark point name must end with<kbd>_start</kbd> and <kbd>_end</kbd>. Each pair of points must otherwise be named identically. Example:

```php
bench\mark('my_mark_start');

// Some code happens here...

bench\mark('my_mark_end');

bench\mark('another_mark_start');

// Some more code happens here...

bench\mark('another_mark_end'); 
```

Please read the [Profiler page](/docs/advanced/#profiling-your-application) for more information.

### Displaying Total Execution Time <a name="displaying-total-execution-time"></a>

------

If you would like to display the total elapsed time from the moment Obullo starts to the moment the final output is sent to the browser, simply place this in one of your view templates:

```php
<?php echo bench\elapsedTime(); ?>
```

You'll notice that it's the same function used in the examples above to calculate the time between two point, except you are <b>not</b> using any parameters. When the parameters are absent, Obullo does not stop the benchmark until right before the final output is sent to the browser. It doesn't matter where you use the function call, the timer will continue to run until the very end.

An alternate way to show your elapsed time in your view files is to use this pseudo-variable, if you prefer not to use the pure PHP:

```php
0.0970
```

**Note:** If you want to benchmark anything within your controller functions you must set your own start/end points.

### Displaying Memory Consumption <a name="displaying-memory-consumption"></a>

------

If your PHP installation is configured with --enable-memory-limit, you can display the amount of memory consumed by the entire system using the following code in one of your view file:

```php
<?php echo bench\memoryUsage(); ?>
```

**Note:** This function can only be used in your view files. The consumption will reflect the total memory used by the entire app.

An alternate way to show your memory usage in your view files is to use this pseudo-variable, if you prefer not to use the pure PHP:

```php
2.02MB
```
