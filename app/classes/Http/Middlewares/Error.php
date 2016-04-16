<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Exception;
use LogicException;
use ErrorException;
use RuntimeException;
use Obullo\Utils\File;
use Obullo\Container\ContainerAwareTrait;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\ErrorMiddlewareInterface;

/**
 * Catch middleware errors
 */
class Error implements ErrorMiddlewareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Invoke middleware
     * 
     * @param mixed         $error    string error or object of Exception
     * @param Request       $request  Psr\Http\Message\ServerRequestInterface
     * @param Response      $response Psr\Http\Message\ResponseInterface
     * @param callable|null $out      final handler
     * 
     * @return object response
     */
    public function __invoke($error, Request $request, Response $response, callable $out = null)
    {
        $container = $this->getContainer();

        if ($container->get('env')->getValue() != 'production') {

            $html = $this->renderHtmlErrorMessage($error);
            
            // $json = $this->renderJsonErrorMessage($error);
        }

        if (is_object($error)) {
            /*
            | Exception Hierarchy
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
            switch ($error) {
            case ($error instanceof Exception):
            case ($error instanceof ErrorException):
            case ($error instanceof LogicException):
            case ($error instanceof RuntimeException):
                $log = new \Log\Error($container->get('logger'));
                $log->message($error);
                break;
            }
        }
        
        // return $response
        //  ->json($json, 500, [], JSON_PRETTY_PRINT);

        return $response
            ->html($html, 500);
    }

    /**
     * Render HTML error page
     *
     * @param error $error error | exception
     * 
     * @return string
     */
    protected function renderHtmlErrorMessage($error)
    {
        $title = 'Http Error';

        if (is_string($error)) {

            $html = $error;

        } elseif (is_object($error)) {
            $html = $this->renderHtmlException($error);
            while ($exception = $error->getPrevious()) {
                $html .= '<h2>Previous exception</h2>';
                $html .= $this->renderHtmlException($exception);
            }
        }
        $header = '<style>
        body{ color: #777575 !important; margin:0 !important; padding:20px !important; font-family:Arial,Verdana,sans-serif !important;font-weight:normal;  }
        h1, h2, h3, h4 {
            margin: 0;
            padding: 0;
            font-weight: normal;
            line-height:48px;
        }
        </style>';

        $output = sprintf(
            "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
            "<title>%s</title>%s</head><body><h1>%s</h1>%s</body></html>",
            $title,
            $header,
            $title,
            $html
        );

        return $output;
    }

    /**
     * Render exception as HTML.
     *
     * @param Exception $exception exception
     *
     * @return string
     */
    protected function renderHtmlException(Exception $exception)
    {
        $html = sprintf('<tr><td><strong>Type:</strong></td><td>%s</td></tr>', get_class($exception));

        if (($message = $exception->getMessage())) {
            $html .= sprintf('<tr><td><strong>Message:</strong></td><td>%s</td></tr>', $message);
        }

        if (($code = $exception->getCode())) {
            $html .= sprintf('<tr><td><strong>Code:</strong></td><td>%s</td></tr>', $code);
        }

        if (($file = File::getSecurePath($exception->getFile()))) {
            $html .= sprintf('<tr><td><strong>File:</strong></td><td>%s</td></tr>', $file);
        }

        if (($line = $exception->getLine())) {
            $html .= sprintf('<tr><td><strong>Line:</strong></td><td>%s</td></tr>', $line);
        }
        $html = "<table>".$html."</table>";

        if (($trace = $exception->getTraceAsString())) {
            $html .= '<h2>Trace</h2>';
            $html .= sprintf('<pre>%s</pre>', htmlentities($trace));
        }
        
        return $html;
    }

    /**
     * Render JSON error
     *
     * @param Exception $exception exception
     * 
     * @return string
     */
    protected function renderJsonErrorMessage(Exception $exception)
    {
        $error = [
            "success" => 0,
            'message' => 'Rest Api Error',
        ];
        $error['exception'] = [];

        do {
            $error['exception'][] = [
                'type' => get_class($exception),
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => File::getSecurePath($exception->getFile()),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        } while ($exception = $exception->getPrevious());
    
        return $error;
    }
}