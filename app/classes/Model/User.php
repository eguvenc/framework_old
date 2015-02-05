<?php

namespace Model;

/**
 * User model
 */
Class User extends \Model
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
        // $this->c->bind('model debug', 'Debug');
    }

    public function test()
    {
        echo 'OK !';
        // $this->modelDebug->test();
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