<?php

namespace Membership;

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
        $this->c->load('service/db');
        $this->c->bind('model debug');
    }

    public function test()
    {
        $this->model->debug->test();
        echo __CLASS__.'<br />';   
    }

    public function save()
    {
        $this->db->insert(
            'users', 
            array(
            'username' => $this->username,
            'email' => $this->email,
            'date' => $this->date,
            )
        );
    }

}