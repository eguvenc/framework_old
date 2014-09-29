<?php

namespace Log\Handlers\FileHandler;

use Obullo\Log\Handler\FileHandler,
    Obullo\Log\Writer\FileWriter;

/**
 * "FileHandler" with "CartridgeFileWriter"
 * 
 * @category  Log
 * @package   Handler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class CartridgeFileWriter
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Handler closure
     * 
     * @var object
     */
    protected $closure;

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct($c)
    {
        $this->closure = function () use ($c) {

            return new FileHandler(
                $c,
                new FileWriter(
                    $c->load('config')['log']
                )
            );
        };
    }

    /**
     * Returns to closure data of handler
     * 
     * @return object closure
     */
    public function getHandler()
    {
        return $this->closure;
    }
}

// END CartridgeFileWriter class

/* End of file CartridgeFileWriter.php */
/* Location: .app/Log/Handlers/FileHandler/CartridgeFileWriter.php */