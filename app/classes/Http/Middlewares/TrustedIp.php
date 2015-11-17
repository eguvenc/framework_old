<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Http\Middleware\MiddlewareInterface;

class TrustedIp implements MiddlewareInterface
{
    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        /**
         * Reverse Proxy IPs : If your server is behind a reverse proxy,
         *                     you must whitelist the proxy IP addresses.
         *                     Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
         */
        $proxyIps = '10.0.1.200,10.0.1.201';
        
        if (! empty($proxyIps)) {

            $server = $request->getServerParams();
            $remoteAddr = isset($server['REMOTE_ADDR']) ? $server['REMOTE_ADDR'] : '0.0.0.0';
            $ipAddress = $this->detectIpAddress($proxyIps, $remoteAddr, $server);
            
            $request = $request->withAttribute('TRUSTED_IP', $ipAddress);
        }

        return $next($request, $response);
    }

    /**
     * Detect client ip address
     * 
     * @param string $proxyIps   white list
     * @param string $remoteAddr client ip
     * @param array  $server     request server parameters
     * 
     * @return string
     */
    protected function detectIpAddress($proxyIps, $remoteAddr, $server)
    {
        $proxyIps = explode(',', str_replace(' ', '', $proxyIps));

        foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP') as $header) {

            $spoof = (isset($server[$header])) ? $server[$header] : false;

            if ($spoof !== false) {

                if (strpos($spoof, ',') !== false) {   // Some proxies typically list the whole chain of IP addresses through which the client has reached us.
                    $spoof = explode(',', $spoof, 2);  // e.g. client_ip, proxy_ip1, proxy_ip2, etc.
                    $spoof = $spoof[0];
                }
                if (! filter_var($spoof, FILTER_VALIDATE_IP)) {
                    $spoof = false;
                } else {
                    break;
                }
            }
        }
        return ($spoof !== false && in_array($remoteAddr, $proxyIps, true)) ? $spoof : $remoteAddr;
    }

}