CHANGE LOG:

Version 1.0.1 Changes

 o Libraries
    - Deleted old session.php from libraries folder.
    - Added new HMVC library. -- added to userguide
    - Added source_url() function to Config.php class, added $suffix parameter to site_url() and added slash '/' support. -- added to user guide
    - Deleted old Pager.php from libraries folder.
    - Added new Profiler Class. -- added to user guide
    - Added new Pager Class / Drivers and Html widgets. --  added to userguide
    - Added auto_base_url() , base_url and source_url functions to config class, updated source_url() function. -- added to user guide
    - Agent and Parser class moved to /Php5 library folder.
    - Added init method chaining support to all Php5 libraries " e.g.: echo agent::instance()->init()->browser(); "
    - Added $no_slash and $uri parameters to Config.php source_url(); function and updated related url helper file.
    
 o Config
    - Removed config/parents.php file from config folder.
    - Added $config['subhelper_prefix'] = 'my_';  to application/config.php file.  -- added to user guide 
    - Added init.php to config/ folder and added date_default_timezone_set() function.
    - Added $config['rewrite_short_tags']   = FALSE; to application/config.php file.
    - Added $config['sess_die_cookie'] = FALSE; to application/config.php
 
 o Loader
    - Added $params_or_no_ins instantiate switch to loader::model(); function. User can
switch off Model instantiate using by boolean like loader::model('model_name', false);  -- added to user guide
    - Added $params_or_no_ins instantiate switch for all Loader Library functions. -- added to user guide
    - changed loader::file('folder/file') as loader::file('folder/file'.EXT)  -- added to user guide
    - renamed OB_DBFactory::init() func as OB_DBFactory::Connect().
    - added loader::model('./outside_folder/model_name')  outside directory  load support 
    - added loader::app_model('subfolder/model_name')  global directory sub folder load support
    - added loader::model('subfolder/model_name')  local directory sub folder load support (thanks to Cj Lazell)
    - added sub folder load support to loader::lib() and loader::app_lib('folder/sub/filename'); functions.
    
 o Database
    - Removed "use_bind_value" constant from constants/db.php file.
    - added ob_query_timer_start() and ob_query_timer_end() functions to DB class for profiling.,
    - added some Codeigniter database result functions result() result_array(); to DB class.
    - added default fetch object support to $this->db->fetch(); and $this->db->fetch_all(); functions. forexample $this->db->fetch(); func.
fetch data as object by default. $this->db->fetch(assoc); fetch data as associative array().
    - added $this->db->row_array() and $this->db->next_row(); functions to DB.php  -- added to userguide
    - added $query->first_row(), $query->last_row(), $query->previous_row() to DB.php, updated next_row(); functions. -- added to userguide
    - added __sleep and __wakeup function to DB.php file.
    - added $use_active_record parameter to loader::database(, $use_active_record = TRUE); function.
    
 o Helpers
    - added new hmvc helper. -- added to userguide
    - renamed form helper validation_errors() function as "form_validate_errors();"  -- added to user guide
    - renamed captcha helper create_captcha() function as "captcha_create();" -- added to user guide 
    - Added source_url() function to url helper. -- added to user guide 
    - Head_tag helper changes, removed $path param from css(); function , we use css('css/filename.css') instead of css('filename', $path = 'css');
    - changed css() function array support in head tag helper
    - js(filename.js), img(filename.ext) functions changed as js(js/filename.js), img(images/filename.ext); -- added to user guide
    - Head_tag helper changes, removed path from js(); function , we use js('js/welcome.js'); instead of js('filename')
    - Removed Content helper, added new View helper file.Depreciated all Content helper functions.
    - added current_url(), current_dir(), current_class(), current_method() functions to Url helper. -- added to user guide
    - added sess_get_flash('', $prefix = '', $suffix = '') func. parameters to session helper drivers.
    - added base_url(), site_url() functions to url helper file. -- added to user guide
    - added $suffix parameter and sharp support to redirect() and anchor() functions.
    - added extra ='' (attributes) param to form_hidden(); function..  -- added to user guide
    - View helper, added view('', (object)$data); support
    - Head tag helper, added script(), script_app(), script_base(), doctype() functions to head tag helper. -- added to user guide
    - added session helper driver extend support, added to all helpers extend support. -- added to user guide
    - added i_request() function to base input helper. -- added to user guide
    - renamed lang_item() function as lang() in lang.php, updated all libraries. -- added to user guide
    - added alnum_upper and alnum_lower options to random_string() function (string helper) -- added to user guide
    - added short_open_tag support to view helper.
    - added sess(); function ( alias of sess_get() ).  -- added to userguide
    - added config['session_die_cookie'] If set TRUE all sessions will destroy when the browser closed, added this 
functionality helpers/session/cookie_driver.php and database_driver.php files.  -- added to userguide
    - added $uri parameter to source_url(); function in url helper. Updated related file /base/Config.php
    - added $no_slash param to Config.php source_url(); function and url helper file.
    
 o Core
    - Removed some reserved controller variables (_libs and _mods)  from obullo/ob class.  -- added to user guide
    - added loaded_helper() function to common.php file, changed bootstrap.php and ob_set_headers func, added loaded helpers track support to profiler class.
    - Renamed ssc class Ssc.
    - @depreciated ob::instance() function, we use this(); function.
    - added meta http-equiv="Content-Type" content="text/html; charset=utf-8"  to system/errors/ob_404.php file.
    - removed LoaderException from register_autoload() function, to play nicely with other autoloaders.
    - removed init() method from PHP5_Library interface.
    - replaced  Obullo::instance() function as this() in all files.
    - removed Obullo/Obullo.php moved all obullo.php contents to Controller.php. Updated Bootstrap.php
    - Depreciated using parent::__global() func in all controllers. We moved it to Obullo/Controller.php , parent::__global() function
works automatically from now on.
    - added log_me(); function to common.php just alias of log_message();
    - added profiler_set($type, key, msg) and profiler_get($key) functions to common.php file, updated all libraries, removed ssc:intance()->_profiler_ variables.
    - added a new controller called App_controller to application/parents folder.
    - Major changes in Global Controllers removed config/parents.php and updated global controller functionality.
    
 o Bug Fixes for 1.0.1
    - loading model from another path bug fixed. added 'models'.DS.
    - ini_set('display_errors', ..); removed from bootstrap and config.php
    - session function all_userdata changed as sess_alldata() in session drivers.
    - removed all php closing tags from all php files (thanks to phpyuz)
    - fixed db.php self::_is_assoc() function bug, removed is_array() added php sizeof() function.
    - fixed class_exists($model, false) // autoload false bug in private loader::_model() function.
    - renamed encrypt class hash() method as hash_encode. (thanks to phpyuz for good suggestions)
    - multiple database connection bug fixed in database/DBFactory.php file. (thanks to Huseyin Basar, he found this bug.)
    - loader::database('', '' , FALSE); active record turn off switch depreciated, we have already this option in config/database.php file.
    - postgreSQL connection bug fixed. (thanks to Julio Max)
    - fixed loader::model('', $objectname); bug , changed param order as loader_model('filename', $object_name, $params); function (thanks to Cj Lazell)
    - loader::model() require '' func changed as require_once ''.
    - a newline bug fixed in profiler.php
    - fixed auto_source_url variable bug in Config.php
    

- added public_folder item to config.php
- added _get_public_path() function to common.php file, updated head_tag and body_tag helper.  
- updated Config.php and url helper public_url() function.
- added [no_space] rule to Validator.php
- added [valid_email_dns] rule to Validator library
- added callback_request[method][request_uri] rule to Validator library
- added Module/core/logs folder, modules has own logs folder from now on.

