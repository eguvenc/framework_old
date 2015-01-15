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

        $this->c->load('service/db');

        $columns = array('username', 'password');
        $values  = array('ersin', '123456');

        echo $this->db->write("INSERT INTO %s", array('users'), $columns, $values);
        
        // $this->db->write("REPLACE INTO %s", array('users'), $data);
        // $this->db->write("UPDATE %s WHERE user_id = ?", array('users'), $data, array(4));
        // $this->db->write("DELETE FROM %s WHERE user_id = ?", array('users'), array(4));
        

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