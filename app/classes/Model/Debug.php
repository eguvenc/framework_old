<?php

namespace Model;

use Model;

/**
 * User model
 */
Class Debug extends Model
{
    public $email;
    public $username;
    public $date;

    /**
     * Loader
     * 
     * @return void
     */
    protected function load()
    {
        $this->c->load('db'); 
    }

    public function test()
    {
        echo __CLASS__.'<br />';
    }

    public function save()
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