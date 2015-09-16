<?php
/*
|--------------------------------------------------------------------------
| Php startup error display file
|--------------------------------------------------------------------------
| Let's off startup errors we catch them using error_get_last(); method.
*/
ini_set('display_startup_errors', 'off');

/*
|--------------------------------------------------------------------------
| Set maximum input items
|--------------------------------------------------------------------------
| How many input variables may be accepted (limit is applied to $_GET, $_POST and $_COOKIE superglobal separately). 
| Use of this directive mitigates the possibility of denial of service  | attacks which use hash collisions. 
| If there are more input variables than specified by this directive, an E_WARNING is issued, 
| and further input variables are truncated from the request.
*/
$maxInputVars = 1000;
$maxInputErrorStr = 'Too much inputs !';

/*
|--------------------------------------------------------------------------
| Error functions
|--------------------------------------------------------------------------
*/
$isAjax = function () {
    if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    }
    return false;
};
$responseHtml = function () use ($maxInputErrorStr) {
    return ;
};
$responseJson = function () use ($maxInputErrorStr) {
    return json_encode(
        array(
            'success' => -1,
            'message' => $maxInputErrorStr
            )
    );
};
$response = function () use ($isAjax, $responseJson, $responseHtml) {
    if ($isAjax()) {
        echo $responseJson();
        die;
    } 
    echo $responseHtml();
    die;
};
/*
|--------------------------------------------------------------------------
| Error Handler for Max $_POST Input Vars
|--------------------------------------------------------------------------
*/
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] = 'POST' && count($_POST, COUNT_RECURSIVE) > $maxInputVars
) {
    $response();
}
/*
|--------------------------------------------------------------------------
| Error Handler for Max $_GET Input Vars
|--------------------------------------------------------------------------
*/
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] = 'GET' && count($_GET, COUNT_RECURSIVE) > $maxInputVars
) {
    $response();
}
/*
|--------------------------------------------------------------------------
| Error Handler for Max $_COOKIE Input Vars
|--------------------------------------------------------------------------
*/
if (isset($_COOKIE) && count($_COOKIE, COUNT_RECURSIVE) > $maxInputVars
) {
    $response();
}