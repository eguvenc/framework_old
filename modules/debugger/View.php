<?php

namespace Debugger;

use Obullo\Debugger\Manager;

class View extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->manager = new Manager($this->c);
    }

    /**
     * Print main html
     *
     * @return string
     */
    public function index()
    {
        $html = $this->c['view']->load(
            'debugger',
            [
                'websocketUrl' => $this->c['config']['http']['debugger']['socket'],
                'debuggerOff'  => (int)$this->c['config']['http']['debugger']['enabled'],
                'debuggerUrl'  => $this->c['app']->uri->getBaseUrl(INDEX_PHP.'/debugger/index?o_debugger=1'),
                'envTab' => '',
                // 'envTab'       => $this->manager->printTab('env'),
            ],
            false
        );
        echo $this->manager->printHtml($html);
    }

    /**
     * Print tab
     * 
     * @param string $tab direction
     * 
     * @return string
     */
    public function printTab($tab = 'http')
    {
        echo $this->manager->printTab($tab);
    }
}