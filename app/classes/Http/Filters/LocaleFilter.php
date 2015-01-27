<?php

namespace Http\Filters;

use Obullo\Container\Container;

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
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     *
     * @param object $c container
     */
    public function __construct(Container $c)
    {
        $this->c = $c['uri'];
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        $locale = $this->c['cookie']->get('locale');
        $languages = $this->c['config']->load('translator')['languages'];

        if ( ! isset($languages[$locale]) OR $locale == false) {
            $locale = $this->c['translator']->getLocale();
        }
        $this->c->load('url')->redirect($locale. '/' . $this->c['uri']->getUriString());
    }
}

// END LocaleFilter class

/* End of file LocaleFilter.php */
/* Location: .Http/Filters/LocaleFilter.php */