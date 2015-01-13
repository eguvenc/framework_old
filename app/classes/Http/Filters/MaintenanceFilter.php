<?php

namespace Http\Filters;

use LogicException;

/**
 * Maintenance filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/router
 */
Class MaintenanceFilter
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * SimpleXmlElement domain
     * 
     * @var object
     */
    protected $domain;

    /**
     * Constructor
     *
     * @param object $c      container
     * @param array  $params config parameters
     */
    public function __construct($c , $params = array())
    {
        $this->c = $c;
        $this->domain = $params['domain'];
    }

    /**
     * Before the controller
     * 
     * @return void
     */
    public function before()
    {
        $this->allWebSiteFilter();       // Filter for all hosts
        $this->subdomainRegexFilter();   // Filter for sub domain regex matches
    }

    /**
     * Do filter for all web site routes
     * 
     * @return void
     */
    protected function allWebSiteFilter()
    {
        if ($this->c['config']->env['application']['all']['maintenance'] == 'down') {
            $this->show503();
        }
    }

    /**
     * Do filter for matched sub.domains
     * 
     * @return void
     */
    protected function subdomainRegexFilter()
    {
        if ( ! is_array($this->domain) AND ! isset($this->domain['regex'])) {
            throw new LogicException(
                sprintf(
                    'Correct your routes.php domain value it must be like this <pre>%s</pre>', 
                    '$c[\'router\']->group( array(\'domain\' => $c[\'config\']->env[\'web\'][\'app\'][\'key\'], .., function () { .. }),.'
                )
            );
        }
        if (isset($this->domain['maintenance']) 
            AND $this->domain['maintenance'] == 'down'
        ) {
            $this->show503();
        }
    }

    /**
     * Show maintenance view and die application
     * 
     * @return void
     */
    protected function show503()
    {
        $this->c['response']->setHttpResponse(503)->sendOutput($this->c['view']->template('errors/maintenance'));
        die;
    }

}

// END MaintenanceFilter class

/* End of file MaintenanceFilter.php */
/* Location: .Http/Filters/MaintenanceFilter.php */