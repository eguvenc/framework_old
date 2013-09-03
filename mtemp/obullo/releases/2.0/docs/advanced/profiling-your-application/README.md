## Profiling Your Application<a name="profiling-your-application"></a>

### Profiling Your Application

------

The Profiler Class will display benchmark results, queries you have run, and $_POST data at the bottom of your pages. This information can be useful during development in order to help with debugging and optimization.

### Initializing the Profiler Class

------

**Important:**  This class does <kbd>NOT</kbd> need to be initialized. It is loaded automatically by the Output Class if profiling is enabled as shown below.

### Enabling the Profiler

------

To enable the profiler place the following function anywhere within your Controller functions:

```php
$this->output->profiler();
```

When enabled a report will be generated in a <b>popup window</b>.

To disable the profiler you will use:

```php
$this->output->profiler(false);
```

Profiler popup window content looks like this

```php
URI String
No URI data exists

Directory / Class / Method
welcome / start / index

Memory Usage
1,009,576 bytes

Benchmarks
Loading Time Base Classes      0.0095

Execution Time ( Welcome / Start / Index )      0.0103
Total Execution Time      0.0199

GET Data
No GET data exists

POST Data
No POST data exists

Loaded Files
Config Files      
Lang Files              profiler
Base Helpers            view
                        head_tag
head_tag
Application Helpers      -
Loaded Helpers          input
                        lang
                        benchmark
lang
benchmark
Local Helpers           -
Libraries               -
Models                  -
Databases               -
Scripts                 welcome
Local Views             MODS\welcome\views\view_welcome.php
Application Views       APP\views\view_base_layout.php
```

### Setting Benchmark Points

------

In order for the Profiler to compile and display your benchmark data you must name your mark points using specific syntax.

Please read the information on setting Benchmark points in Benchmark Helper page.