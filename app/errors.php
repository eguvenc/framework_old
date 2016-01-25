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
$app->error(
    function (ErrorException $e) use ($container) {

        $log = new Obullo\Error\Log($container->get('logger'));
        $log->error($e);

        return ! $continue = false;   // Whether to continue show native errors
    }
);
/*
|--------------------------------------------------------------------------
| Database and Other Runtime Exceptions
|--------------------------------------------------------------------------
*/
$app->error(
    function (RuntimeException $e) use ($container) {

        $log = new Obullo\Error\Log($container->get('logger'));
        $log->error($e);
    }
);
/*
|--------------------------------------------------------------------------
| Logic Exceptions
|--------------------------------------------------------------------------
*/
$app->error(
    function (LogicException $e) use ($container) {

        $log = new Obullo\Error\Log($container->get('logger'));
        $log->error($e);
    }
);
/*
|--------------------------------------------------------------------------
| Php Fatal Errors
|--------------------------------------------------------------------------
*/
$app->fatal(
    function (ErrorException $e) use ($container) {

        $log = new Obullo\Error\Log($container->get('logger'));
        $log->error($e);
    }
);