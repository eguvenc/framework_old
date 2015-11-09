<?php
/*
|--------------------------------------------------------------------------
| Global Errors
|--------------------------------------------------------------------------
| Specifies the your application global exceptions & errors.
|
|  Known Exception Hierarchy
|
|   - Exception
|       - ErrorException
|       - LogicException
|           - BadFunctionCallException
|               - BadMethodCallException
|           - DomainException
|           - InvalidArgumentException
|           - LengthException
|           - OutOfRangeException
|       - RuntimeException
|           - PDOException
|           - OutOfBoundsException
|           - OverflowException
|           - RangeException
|           - UnderflowException
|           - UnexpectedValueException
*/
/*
|--------------------------------------------------------------------------
| Php Native Errors
|--------------------------------------------------------------------------
*/
$c['app']->error(
    function (ErrorException $e) use ($c) {
        
        $log = new Obullo\Error\Log($c['logger']);
        $log->error($e);

        return ! $continue = false;   // Whether to continue show native errors
    }
);
/*
|--------------------------------------------------------------------------
| Database and Other Runtime Exceptions
|--------------------------------------------------------------------------
*/
$c['app']->error(
    function (RuntimeException $e) use ($c) {
        $log = new Obullo\Error\Log($c['logger']);
        $log->error($e);
    }
);
/*
|--------------------------------------------------------------------------
| Logic Exceptions
|--------------------------------------------------------------------------
*/
$c['app']->error(
    function (LogicException $e) use ($c) {
        $log = new Obullo\Error\Log($c['logger']);
        $log->error($e);
    }
);
/*
|--------------------------------------------------------------------------
| Php Fatal Errors
|--------------------------------------------------------------------------
*/
$c['app']->fatal(
    function (ErrorException $e) use ($c) {
        $log = new Obullo\Error\Log($c['logger']);
        $log->error($e);
    }
);