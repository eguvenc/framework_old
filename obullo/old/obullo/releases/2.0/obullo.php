<?php

/**
 * Obullo Class
 * 
 * @category  Core
 * @package   Obullo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/obullo
 */

/**
 * Run framework
 */
function Framework_Run() {

    $start = microtime(true);

    global $config, $uri, $router, $response, $logger;

    /*
     * ------------------------------------------------------
     *  Instantiate the hooks class
     * ------------------------------------------------------
     */
    if ($config['enable_hooks']) {
        global $hooks;
        /*
         * ------------------------------------------------------
         *  Is there a "pre_system" hook?
         * ------------------------------------------------------
         */
        $hooks->call('pre_system');
    }
    /*
     * ------------------------------------------------------
     *  Sanitize Inputs
     * ------------------------------------------------------
     */
    if ($config['enable_query_strings'] == false) {  // Is $_GET data allowed ? If not we'll set the $_GET to an empty array
        $_GET = array();
    }
    $_GET  = cleanInputData($_GET);
    $_POST = cleanInputData($_POST);  // Clean $_POST Data
    $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']); // Sanitize PHP_SELF

    if ($config['csrf_protection']) {  // CSRF Protection check
        global $security;
        $security->initCsrf();
        $security->csrfVerify();
    }
    // Clean $_COOKIE Data
    // Also get rid of specially treated cookies that might be set by a server
    // or silly application, that are of no use to application anyway
    // but that when present will trip our 'Disallowed Key Characters' alarm
    // http://www.ietf.org/rfc/rfc2109.txt
    // note that the key names below are single quoted strings, and are not PHP variables
    unset($_COOKIE['$Version']);
    unset($_COOKIE['$Path']);
    unset($_COOKIE['$Domain']);

    $_COOKIE = cleanInputData($_COOKIE);

    $logger->debug('Global POST and COOKIE data sanitized');
    /*
     * ------------------------------------------------------
     *  Log requests
     * ------------------------------------------------------
     */
    if ($logger->getProperty('enabled')) {
        $logger->debug('$_REQUEST_URI: ' .$uri->getRequestUri());
        if (ENV == 'DEBUG' OR ENV == 'TEST') {
            $logger->debug('$_COOKIE: ', $_COOKIE);
            $logger->debug('$_POST: ', $_POST);
            $logger->debug('$_GET: ', $_GET);
        }
    }
    /*
     * ------------------------------------------------------
     *  Load core components
     * ------------------------------------------------------
     */
    $pageUri    = "{$router->fetchDirectory()} / {$router->fetchClass()} / {$router->fetchMethod()}";
    $controller = PUBLIC_DIR . $router->fetchDirectory() . DS . 'controller' . DS . $router->fetchClass() . EXT;

    if ( ! file_exists($controller)) {
        $response->show404($pageUri);
    }
    /*
     * ------------------------------------------------------
     *  Is there a "pre_controller" hook?
     * ------------------------------------------------------
     */
    if ($config['enable_hooks']) {
        $hooks->call('pre_controller');
    }

    include $controller;  // call the controller.  $c variable now Available in HERE !!

    // Do not run private methods. ( _output, _remap, _getInstance .. )

    if (strncmp($router->fetchMethod(), '_', 1) == 0 
        OR in_array(strtolower($router->fetchMethod()), array_map('strtolower', get_class_methods('Controller')))
    ) {
        $response->show404($pageUri);
    }
    /*
     * ------------------------------------------------------
     *  Is there a "post_controller_constructor" hook?
     * ------------------------------------------------------
     */
    if ($config['enable_hooks']) {
        $hooks->call('post_controller_constructor');
    }
    $storedMethods = array_keys($c->_controllerAppMethods);

    if ( ! in_array(strtolower($router->fetchMethod()), $storedMethods)) {  // Check method exist or not
        $response->show404($pageUri);
    }

    $arguments = array_slice($c->uri->rsegments, 2);

    if (method_exists($c, '_remap')) {  // Is there a "remap" function? If so, we call it instead
        $c->_remap($router->fetchMethod(), $arguments);
    } else {
        // Call the requested method. Any URI segments present (besides the directory / class / method) 
        // will be passed to the method for convenience
        // directory = 0, class = 1,  ( arguments = 2) ( @deprecated  method = 2 method always = index )
        call_user_func_array(array($c, $router->fetchMethod()), $arguments);
    }
    /*
     * ------------------------------------------------------
     *  Is there a "post_controller" hook?
     * ------------------------------------------------------
     */
    if ($config['enable_hooks']) {
        $hooks->call('post_controller');
    }
    /*
     * ------------------------------------------------------
     *  Send the final rendered output to the browser
     * ------------------------------------------------------
     */
    if ($config['enable_hooks']) {
        if ($hooks->call('display_override') === false) {
            $response->_sendOutput();  // Send the final rendered output to the browser
        }
    } else {
        $response->_sendOutput();    // Send the final rendered output to the browser
    }
    /*
     * ------------------------------------------------------
     *  Is there a "post_system" hook?
     * ------------------------------------------------------
     */
    if ($config['enable_hooks']) {
        $hooks->call('post_system');
    }

    $time = microtime(true) - $start;

    $extra = array();
    if ($config['log_benchmark']) {     // Do we need to generate benchmark data ? If so, enable and run it.
        $usage = 'memory_get_usage() function not found on your php configuration.';
        if (function_exists('memory_get_usage') AND ($usage = memory_get_usage()) != '') {
            $usage = number_format($usage) . ' bytes';
        }
        $extra = array('time' => number_format($time, 4), 'memory' => $usage);
    }

    $logger->debug('Final output sent to browser', $extra);
        
}
// end construct

// Common Functions
// ------------------------------------------------------------------------

/**
 * Clean Input Data
 *
 * This is a helper function. It escapes data and
 * standardizes newline characters to \n
 *
 * @access   private
 * @param    string
 * @return   string
 */
function cleanInputData($str)
{
    global $config;
    if (is_array($str)) {
        $new_array = array();
        foreach ($str as $key => $val) {
            $new_array[cleanInputKeys($key)] = cleanInputData($val);
        }
        return $new_array;
    }
    $str = removeInvisibleCharacters($str); // Remove control characters
    if ($config['global_xss_filtering']) {  // Should we filter the input data?
        global $security;
        $str = $security->xssClean($str);
    }
    return $str;
}

// ------------------------------------------------------------------------

/**
 * Clean Keys
 *
 * This is a helper function. To prevent malicious users
 * from trying to exploit keys we make sure that keys are
 * only named with alpha-numeric text and a few other items.
 *
 * @access   private
 * @param    string
 * @return   string
 */
function cleanInputKeys($str)
{
    if ( ! preg_match("/^[a-z0-9:_\/-]+$/i", $str)) {
        die('Disallowed Key Characters.');
    }
    return $str;
}

// --------------------------------------------------------------------

/**
 * Load configuration files.
 * 
 * @access    private
 * @param     string $filename file name
 * @param     string $var variable of the file
 * @param     string $folder folder of the file
 * @param     string $ext extension of the file
 * @return    array
 */
function getStatic($filename = 'config', $var = '', $folder = '', $ext = '')
{
    static $loaded = array();
    static $variables = array();

    $key = trim($folder . DS . $filename . $ext);

    if ( ! isset($loaded[$key])) {
        include $folder . DS . $filename . $ext;
        if ($var == '') {
            $var = &$filename;
        }
        if ( ! isset($$var) OR ! is_array($$var)) {
            die('The configuration file ' . $folder . DS . $filename . $ext . ' variable name must be same with filename.');
        }
        $variables[$key] = & $$var;
        $loaded[$key] = $key;
    }
    return $variables[$key];
}

// --------------------------------------------------------------------

/**
 * Get configuration files from "app/config/$env/" folder.
 * 
 * @access   public
 * @param    string $filename
 * @param    string $var
 * @return   array
 */
function getConfig($filename = 'config', $var = '', $folder = '')
{
    global $config;
    $folder = ($folder == '') ? APP . 'config' : $folder;

    if (in_array($filename, $config['environment_config_files'])) {
        $folder = APP . 'config' . DS . strtolower(ENV);
    } else {
        $sub_path = '';
        if (strpos($filename, '/') > 0) {   // sub folder support
            $exp = explode('/', $filename);
            $filename = end($exp);
            array_pop($exp);    // pop the last element end of the array
            $sub_path = DS . implode(DS, $exp);
        }
        $folder = $folder . $sub_path;
    }
    return getStatic($filename, $var, $folder, EXT);
}

// ------------------------------------------------------------------------

/**
 * Get the schema configuration.
 * 
 * @param  string $filename schema filename
 * @return array  $schema array
 */
function getSchema($filename)
{
    return getConfig($filename, '', APP . 'schemas');
}

// --------------------------------------------------------------------

/**
 * Grab the Controller Instance
 *
 * @access public
 * @param object $new_istance  
 */
function getInstance($newInstance = '')
{
    if (is_object($newInstance)) { // fixed HMVC object type of integer bug.
        Controller::$instance = $newInstance;
    }
    return Controller::$instance;
}

// ------------------------------------------------------------------------

/**
 * Autoload php files.
 *
 * @access private
 * 
 * @param string $packageRealname
 * 
 * @return void
 */
function autoloader($packageRealname)
{
    if (class_exists($packageRealname, false)) {  // https://github.com/facebook/hiphop-php/issues/947
        return;
    }
    global $packages;

    $className = ltrim($packageRealname, '\\');  // http://www.php-fig.org/psr/psr-0/
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . EXT;
    $packageName = strtolower($className);

    // exit($packageName);

    if (isset($packages['dependencies'][$packageName])) {  // check is it a Package ?
        $version = $packages['dependencies'][$packageName]['version'];
        $fileUrl = PACKAGES . $packageName . DIRECTORY_SEPARATOR . 'releases' . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR .$packageName . EXT;

        include_once $fileUrl;
        return;
    } else {
        if (file_exists(CLASSES . $fileName)) {  // If its not a package, load User Classes from Classes Directory.
            include_once CLASSES . $fileName;
        }
    }
}
spl_autoload_register('autoloader', true);

// --------------------------------------------------------------------

/**
 * Check requested package whether to installed.
 *
 * @access public
 * @param  string $package
 * @return bool
 */
function packageExists($package)
{
    global $packages;
    if (isset($packages['dependencies'][$package])) {
        return true;
    }
    return false;
}

// --------------------------------------------------------------------

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access   public
 * @param    string
 * @return   string
 */
function removeInvisibleCharacters($str, $url_encoded = true)
{
    $non_displayables = array();  // every control character except newline (dec 10)
    if ($url_encoded) {           // carriage return (dec 13), and horizontal tab (dec 09)
        $non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/';   // url encoded 16-31
    }
    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';   // 00-08, 11, 12, 14-31, 127
    do {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    } while ($count);

    return $str;
}

// Exception & Errors
// ------------------------------------------------------------------------

/**
 * Catch All Exceptions
 *
 * @access private
 * @param  object $e
 * @return void
 */
function exceptionsHandler($e, $type = '')
{
    global $packages, $config, $logger;

    $shutdownErrors = array(
        'ERROR' => 'ERROR', // E_ERROR 
        'PARSE ERROR' => 'PARSE ERROR', // E_PARSE
        'COMPILE ERROR' => 'COMPILE ERROR', // E_COMPILE_ERROR   
        'USER FATAL ERROR' => 'USER FATAL ERROR', // E_USER_ERROR
    );
    $shutdownError = false;
    if (isset($shutdownErrors[$type])) {  // We couldn't use any object for shutdown errors.
        $error = new Error; // Load error package.

        $shutdownError = true;
        $type = ucwords(strtolower($type));
        $code = $e->getCode();
        $level = $config['error_reporting'];

        if (defined('STDIN')) {  // If Command Line Request.
            echo $type . ': ' . $e->getMessage() . ' File: ' . $error->getSecurePath($e->getFile()) . ' Line: ' . $e->getLine() . "\n";

            $cmdType = (defined('TASK')) ? 'Task' : 'Cmd';
            $logger->error('(' . $cmdType . ') ' . $type . ': ' . $e->getMessage() . ' ' . $error->getSecurePath($e->getFile()) . ' ' . $e->getLine());

            return;
        }

        if ($level > 0 OR is_string($level)) {  // If user want to display all errors
            if (is_numeric($level)) {
                switch ($level) {
                case 0:
                    return;
                    break;
                case 1:
                    include PACKAGES . 'exceptions' . DS . 'releases' . DS . $packages['dependencies']['exceptions']['version'] . DS . 'src' . DS . 'error' . EXT;
                    return;
                    break;
                }
            }
            $rules = $error->parseRegex($level);
            if ($rules == false) {
                return;
            }
            $allowedErrors = $error->getAllowedErrors($rules);  // Check displaying error enabled for current error.

            if (isset($allowedErrors[$code])) {
                include PACKAGES . 'exceptions' . DS . 'releases' . DS . $packages['dependencies']['exceptions']['version'] . DS . 'src' . DS . 'error' . EXT;
            }
        } else {  // If error_reporting = 0, we show a blank page template.
            include APP . 'errors' . DS . 'disabled_error' . EXT;
        }

        $logger->error($type . ': ' . $e->getMessage() . ' ' . $error->getSecurePath($e->getFile()) . ' ' . $e->getLine());
    } else {  // Is It Exception ? Initialize to Exceptions Component.
        $exception = new Exceptions;
        $exception->write($e, $type);
    }
    return;
}

// --------------------------------------------------------------------

/**
 * Main Error Handler
 * Predefined error constants
 * http://usphp.com/manual/en/errorfunc.constants.php
 * 
 * @access private
 * @param int $errno
 * @param string $errstr
 * @param string $errfile
 * @param int $errline
 */
function errorHandler($errno, $errstr, $errfile, $errline)
{
    if ($errno == 0) {
        return;
    }
    switch ($errno) {
    case '1': $type = 'ERROR';
        break; // E_WARNING
    case '2': $type = 'WARNING';
        break; // E_WARNING
    case '4': $type = 'PARSE ERROR';
        break; // E_PARSE
    case '8': $type = 'NOTICE';
        break; // E_NOTICE
    case '16': $type = 'CORE ERROR';
        break; // E_CORE_ERROR
    case '32': $type = "CORE WARNING";
        break; // E_CORE_WARNING
    case '64': $type = 'COMPILE ERROR';
        break; // E_COMPILE_ERROR
    case '128': $type = 'COMPILE WARNING';
        break; // E_COMPILE_WARNING
    case '256': $type = 'USER FATAL ERROR';
        break; // E_USER_ERROR
    case '512': $type = 'USER WARNING';
        break; // E_USER_WARNING
    case '1024': $type = 'USER NOTICE';
        break; // E_USER_NOTICE
    case '2048': $type = 'STRICT ERROR';
        break; // E_STRICT
    case '4096': $type = 'RECOVERABLE ERROR';
        break; // E_RECOVERABLE_ERROR
    case '8192': $type = 'DEPRECATED ERROR';
        break; // E_DEPRECATED
    case '16384': $type = 'USER DEPRECATED ERROR';
        break; // E_USER_DEPRECATED
    case '30719': $type = 'ERROR';
        break; // E_ALL
    }
    exceptionsHandler(new ErrorException($errstr, $errno, 0, $errfile, $errline), $type);
    return;
}

// -------------------------------------------------------------------- 

/**
 * Catch last occured errors.
 *
 * @access private
 * @return void
 */
function shutdownHandler()
{
    $error = error_get_last();
    if (!$error) {
        return;
    }
    ob_get_level() AND ob_clean(); // Clean the output buffer

    $shutdownErrors = array(
        '1' => 'ERROR', // E_ERROR 
        '4' => 'PARSE ERROR', // E_PARSE
        '64' => 'COMPILE ERROR', // E_COMPILE_ERROR
        '256' => 'USER FATAL ERROR', // E_USER_ERROR
    );
    $type = (isset($shutdownErrors[$error['type']])) ? $shutdownErrors[$error['type']] : '';
    exceptionsHandler(new ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']), $type);
}

// --------------------------------------------------------------------

set_error_handler('errorHandler');
set_exception_handler('exceptionsHandler');
register_shutdown_function('shutdownHandler');

// END obullo.php File

/* End of file obullo.php
/* Location: ./packages/obullo/releases/2.0/obullo.php */