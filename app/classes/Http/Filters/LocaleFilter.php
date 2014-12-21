<?php

namespace Http\Filters;

/**
 * Locale filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class LocaleFilter
{
    /**
     * Url
     * 
     * @var string
     */
    protected $url;
        
    /**
     * Cookie
     * 
     * @var object
     */
    protected $cookie;

    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct($c)
    {
        $this->url = $c->load('url');
        $this->cookie = $c->load('cookie');
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        $locale = $this->cookie->get('locale');
        $languages = $this->c['config']->load('translator')['languages'];

        if ( ! isset($languages[$locale])) {
            $locale = $this->translator->getLocale();
        }
        $this->url->redirect($locale. '/' . $this->uri->getUriString());
    }
}

// END LocaleFilter class

/* End of file LocaleFilter.php */
/* Location: .Http/Filters/LocaleFilter.php */