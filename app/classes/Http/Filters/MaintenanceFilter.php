<?php

namespace Http\Filters;

use LogicException,
    Obullo\Container\Container;

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
    public function __construct(Container $c , $params = array())
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
        $this->domainFilter();   // Filter for sub domain regex matches
    }

    /**
     * Do filter for matched sub.domains or domain reges
     * 
     * @return void
     */
    protected function domainFilter()
    {
        if ($this->c['config']->env['domain']['root']['maintenance'] == 'down') {  // Filter for all domains
            $this->show503();
        }
        if ( ! is_array($this->domain) AND ! isset($this->domain['regex'])) {
            throw new LogicException(
                sprintf(
                    'Correct your routes.php domain value it must be like this <pre>%s</pre>', 
                    '$c[\'router\']->group( array(\'domain\' => $c[\'config\']->env[\'domain\'][\'key\'], .., function () { .. }),.'
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