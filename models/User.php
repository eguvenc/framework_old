<?php

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

        // echo 'new instance<br>';
    }

    public function test()
    {
        
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