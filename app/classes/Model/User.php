<?php

namespace Model;

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
        $this->c->load('db'); 

        echo 'new instance<br>';
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