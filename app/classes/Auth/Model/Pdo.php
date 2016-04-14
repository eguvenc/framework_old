<?php

namespace Auth\Model;

use Obullo\Authentication\Model\Pdo as ModelPdo;

/**
 * Database Model
 * 
 * @copyright 2009-2016 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Pdo extends ModelPdo
{
    /**
     * Connect to database service
     * 
     * @return void
     */
    public function connect()
    {
        $this->db = $this->getContainer()->get('database')->shared(
            [
                'connection' => 'default'
            ]
        );
    }

    /**
     * Build select fields
     * 
     * @return void
     */
    public function getFields()
    {
        return implode(",", $this->fields);  // Build sql select fields
    }

}