<?php

namespace Widgets\Tutorials;

Class Hello_World extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('view');
        // $this->c->load('service/db');

        // $this->config->load('database');

        // echo $this->config['database']['key']['db']->username;

        // $this->c['config']->load('cache');

        // echo $this->config['cache']['redis']['servers'][0]['hostname'];

        // $columns = array('username', 'password');
        // $values  = array('ersin', '123456');

        // $data = array('username' => 'ersin', 'password' => "asd'^sd");

        // $this->db->query("SELECT * FROM %s WHERE user_id = ?", array('users'), array(5));
        // $this->db->query("INSERT INTO %s", array('users'), $data);
        // $this->db->query("REPLACE INTO %s", array('users'), $data);

        // $this->db->query("UPDATE %s WHERE user_id = ?", array('users'), $data, array(4));
        // $this->db->query("DELETE FROM %s WHERE user_id = ?", array('users'), array(4));
        

    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'hello_world',
            function () {
                $this->assign('title', 'Welcome to Obullo !');
            }
        );
    }
}

/* End of file hello_world.php */
/* Location: .controllers/widgets/tutorials/hello_world.php */