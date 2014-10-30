<?php

namespace Http\Filters;

use LogicException,
    SimpleXmlElement;

/**
 * Maintenance filter
 *
 * @category  Route
 * @package   Filters
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/router
 */
Class MaintenanceFilter
{
    /**
     * View class
     * 
     * @var object
     */
    protected $view;

    /**
     * SimpleXmlElement domain element
     * 
     * @var object
     */
    protected $domain;

    /**
     * Config class
     * 
     * @var object
     */
    protected $config;

    /**
     * Response class
     * 
     * @var object
     */
    protected $response;

    /**
     * Constructor
     *
     * @param object $c      container
     * @param array  $params config parameters
     * 
     * @return void
     */
    public function __construct($c , $params = array())
    {
        $this->view = $c['view'];
        $this->domain = $params['domain'];
        $this->config = $c['config'];
        $this->response = $c['response'];

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
        if ($this->config->xml()->route->all->attributes()->maintenance == 'down') {
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
        if ( ! $this->domain instanceof SimpleXmlElement) {
            throw new LogicException(
                sprintf(
                    'Correct your routes.php domain value it must be like this <pre>%s</pre>', 
                    '$c[\'router\']->group( array(\'domain\' => $c[\'config\']->xml()->route->$key->attributes()->$item, .., function () { .. }),.'
                )
            );
        }
        $name = $this->domain->getName();  // Get xml route name
        
        if (isset($this->config->xml()->route->{$name}->attributes()->regex) 
            AND $this->config->xml()->route->{$name}->attributes()->maintenance == 'down'
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
        $this->response->setHttpResponse(503)->sendOutput($this->view->template('errors/maintenance'));
        die;
    }

}

// END MaintenanceFilter class

/* End of file MaintenanceFilter.php */
/* Location: .Http/Filters/MaintenanceFilter.php */