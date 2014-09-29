<?php

namespace Log\Handlers\MongoHandler;

use Obullo\Log\Handler\MongoHandler,
    Obullo\Log\Writer\MongoWriter;

/**
 * "MongoHandler" with "CartridgeMongoWriter"
 * 
 * @category  Log
 * @package   Handler
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/log
 */
Class CartridgeMongoWriter
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
            return new MongoHandler(
                $c,
                new MongoWriter(
                    $c->load('service/provider/mongo', 'db'),  // Mongo client instance
                    array(
                        'database' => 'db',
                        'collection' => 'logs',
                        'save_options' => null,
                        'format' => array(
                            'context' => 'array',  // json
                            'extra'   => 'array'   // json
                        ),
                    )
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

// END CartridgeMongoWriter class

/* End of file CartridgeMongoWriter.php */
/* Location: .app/Log/Handlers/MongoHandler/CartridgeMongoWriter.php */