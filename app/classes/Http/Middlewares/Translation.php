<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Config\ConfigInterface as Config;

class Translation implements MiddlewareInterface
{
    /**
     * Translator config
     * 
     * @var array
     */
    public $config;

    /**
     * Cookie value
     * 
     * @var string
     */
    public $localeCookie;

    /**
     * Constructor
     * 
     * @param Config $config config
     */
    public function __construct(Config $config)
    {
        $this->config = $config->load('translator');
    }

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
        $this->localeCookie = $this->readCookie();
        $this->setLocale();

        return $next($request, $response);
    }

    /**
     * Read "locale" cookie value
     * 
     * @return string|null
     */
    public function readCookie()
    {
        $name = $this->config['cookie']['name'];
        $cookies = $this->request->getCookieParams();

        return isset($cookies[$name]) ? $cookies[$name] : null;
    }

    /**
     * Set default locale
     * 
     * @return void
     */
    public function setLocale()
    {
        if (defined('STDIN')) { // Disable console & task errors
            return;
        }
        if ($this->setByUri()) {  // Sets using http://example.com/en/welcome first segment of uri
            return;
        }
        if ($this->setByOldCookie()) {   // Sets by reading old cookie 
            return;
        }
        if ($this->setByBrowserDefault()) {  // Sets by detecting browser language using intl extension  
            return;
        }
        $this->setDefault();  // Set using default language which is configured in translator config
    }

    /**
     * Set using uri http GET request
     *
     * @return bool
     */
    public function setByUri()
    {
        if ($this->config['uri']['segment']) {

            $segment = $this->uri->segment($this->config['uri']['segmentNumber']);  // Set via URI Segment

            if (! empty($segment)) {
                $bool = ($this->localeCookie == $segment) ? false : true; // Do not write if cookie == segment value same
                if ($this->translator->setLocale($segment, $bool)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Set using browser old cookie
     *
     * @return bool
     */
    public function setByOldCookie()
    {       
        if (! empty($this->localeCookie)) {                           // If we have a cookie then set locale using cookie.
            $this->translator->setLocale($this->localeCookie, false); // Do not write to cookie just set variable.
            return true;
        }
        return false;
    }

    /**
     * Set using php intl extension
     *
     * @return bool
     */
    public function setByBrowserDefault()
    {
        $intl = extension_loaded('intl');     // Intl extension should be enabled.

        if ($intl == false) {
            $this->logger->notice('Install php intl extension to enable detecting browser language feature.');
            return false;
        }
        $server = $this->request->getServerParams();

        if (isset($server['HTTP_ACCEPT_LANGUAGE']) && $intl) {   // Set via browser default value
            $default = strstr(\Locale::acceptFromHttp($server['HTTP_ACCEPT_LANGUAGE']), '_', true);
            $this->translator->setLocale($default);
            return true;
        }
        return false;
    }

    /**
     * Set using alternative default language
     *
     * @return void
     */
    public function setDefault()
    {
        $this->translator->setLocale($this->translator->getDefault());
    }

}