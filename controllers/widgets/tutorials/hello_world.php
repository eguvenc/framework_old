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
        $this->c->load('service/query as db');
        $this->c->load('view');
        
        // $columns = array('username', 'password');
        // $values  = array('ersin', '123456');

        // $data = array('username' => 'ersin', 'password' => "asd'^sd");

        // $this->db->query("SELECT * FROM %s WHERE user_id = ?", array('users'), (1));

        // $this->db->query("INSERT INTO users %s", array(['@insert' => $data]));  
        // $this->db->query("REPLACE INTO users %s", array('$replace' => $data));
        // $this->db->query("UPDATE users SET %s WHERE id = ?", array(['@update' => $data]), array(4));
        
        // $this->db->query("DELETE FROM users WHERE IN (%s)", array(['@in' => [1,2,3]]), array(4));
        // $this->db->query("DELETE FROM users WHERE OR (%s)", array(['@and' => ['u' => 's', 'a' => 'B']]), array(4));
        // $this->db->query("DELETE FROM users WHERE OR (%s)", array(['@or' => ['u' => 's', 'a' => 'B']]), array(4));
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
            [
                'title' => 'Welcome to Obullo !',
            ],
            'welcome'
        );
    }
}

/* End of file hello_world.php */
/* Location: .controllers/widgets/tutorials/hello_world.php */