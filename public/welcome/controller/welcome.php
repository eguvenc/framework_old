<?php

namespace Welcome;

/**
 * Welcome\Welcome
 */
Class Welcome extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('view');

        $this->config->env['web']['route']['all']['maintenance'] = 'up';
        $this->config->write();

        var_dump($this->config->env);
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $array = array(
                'log' =>   array(
                    'control' => array(
                        'enabled' => true,
                        'output'  => false,
                    ),
                    'default' => array(
                        'channel' => 'system',       // Default channel name should be general.
                    ),
                    'file' => array(
                        'path' => array(
                            'http'  => 'data/logs/http.log',  // Http requests log path  ( Only for File Handler )
                            'cli'   => 'data/logs/cli.log',   // Cli log path  
                            'ajax'  => 'data/logs/ajax.log',  // Ajax log path
                        )
                    ),
                    'format' => array(
                        'line' => '[%datetime%] %channel%.%level%: --> %message% %context% %extra%\n',  // This format just for line based log drivers.
                        'date' =>  'Y-m-d H:i:s',
                    ),
                    'extra' => array(
                        'queries'   => true,       // If true "all" SQL Queries gets logged.
                        'benchmark' => true,       // If true "all" Application Benchmarks gets logged.
                    ),
                    'queue' => array(
                        'channel' => 'Log',
                        'route' => gethostname(). '.Logger',
                        'worker' => 'Workers\Logger',
                        'delay' => 0,
                        'workers' => array(
                            'logging' => false     // On / Off Queue workers logging functionality.
                        ), 
                    )
            )
        );

        $array2 = array(
            'log' =>   array(
                'control' => array(
                    'enabled' => true,
                    'output'  => true,
                ),
                'file' => array(
                        'path' => array(
                            'http'  => 'data/logs/sadasdasds',  // Http requests log path  ( Only for File Handler )
                            'cli'   => 'data/logs/cli.log',   // Cli log path  
                            'ajax'  => 'data/logs/ajax.log',  // Ajax log path
                        )
                ),
            )
        );

       //  print_r(array_merge($array['log'], $array2['log']));

        $this->view->load(
            'welcome',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer', false));
            }
        );
    }
}


/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */