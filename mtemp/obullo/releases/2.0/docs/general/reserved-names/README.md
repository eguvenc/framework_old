### Reserved Names <a name="reserved-names"></a>

------

In order to help out, Obullo uses a series of functions and names in its operation. Because of this, some names cannot be used by a developer. Following is a list of reserved names that cannot be used.

### Controller names <a name="controller-names"></a>

------

Since your controller classes will extend the main application controller you must be careful not to name your functions identically to the ones used by that class, otherwise your local functions will override them. The following is a list of reserved names. Do not name your controller functions any of these:

- Controller
- _instance()
- index()
- _remap()
- _output()
- _output_hmvc()

#### Functions

------

- ob_include_files()
- ob_set_headers()
- ob_system_run()
- ob_system_close()
- isReallyWritable()
- setStatusHeader()
- ob_autoload()
- load_class()
- getStatic()
- getConfig()
- config_item()
- db()
- is_php()
- showHttpError()
- Obullo_ErrorTemplate()
- Obullo_ErrorHandler()
- showError()
- show404()
- log_me()
- lang()
- this()
- __merge_autoloaders()
- All Helper Functions

#### Variables

------

- $_ob
- $config
- $lang
- $routes

#### Reserved $GLOBALS variables

------

- $GLOBALS['d']
- $GLOBALS['c']
- $GLOBALS['m']
- $GLOBALS['s']

#### Constants

------

- DS
- EXT
- ROOT
- MODS
- PHP_PATH
- FCPATH
- SELF
- BASE
- APP
- CMD
- TASK
- OBULLO_VERSION

##### File Constants

* 0644
* 0666
* 0755
* 0777
* 'rb'
* 'r+b'
* 'wb'
* w+b
* ab
* 'a+b'
* 'xb'
* 'x+b'

##### Database Constants

* param_null
* PARAM_INT
* PARAM_STR
* param_lob
* param_stmt
* param_bool
* param_inout
* lazy
* assoc
* num
* both
* obj
* row
* bound
* column
* as_class
* into
* func
* named
* key_pair
* group
* unique
* class_type
* serialize
* props_late
* ori_next
* ori_prior
* ori_first
* ori_last
* ori_abs
* ori_rel