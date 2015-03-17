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
        $this->c['query as db'];
        // $this->c['model.debug'] = function () {
        //     return new \Model\Debug; 
        // };
    }

    public function test()
    {
        echo 'OK !';
        $this->c['model.debug']->test();
        // $this->modelDebug->test();
    }

    public function save()
    {
        $this->db->insert(
            'users',
            [
                $this->username, 
                $this->email, 
                $this->date
            ]
        );

        // $this->db->query(
        //     'INSERT INTO users (%s,%s,%s) VALUES (?,?,?)', 
        //     [
        //         'username',
        //         'email', 
        //         'date'
        //     ],
        //     [
        //         $this->username, 
        //         $this->email, 
        //         $this->date
        //     ]
        // );
    }

}