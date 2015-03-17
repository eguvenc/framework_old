<?php

namespace Model\Membership;

use Model;

/**
 * User model
 */
Class User extends Model
{
    public $email;
    public $username;
    public $date;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['db'];
    }

    public function test()
    {
        $this->modelDebug->test();
        echo __CLASS__.'<br />';   
    }

    public function insert()
    {
        $this->db->query(
            'INSERT INTO users (%s,%s,%s) VALUES (?,?,?)', 
            [
                'username',
                'email', 
                $this->db->protect('date')
            ],
            [
                $this->username, 
                $this->email, 
                $this->date
            ]
        );

    }

}