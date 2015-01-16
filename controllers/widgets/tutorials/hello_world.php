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
        $this->c->load('service/db');

        // $columns = array('username', 'password');
        // $values  = array('ersin', '123456');

        // $data = array('username' => 'ersin', 'password' => "asd'^sd");

        
        $this->db->query(
            "SELECT * FROM users WHERE user_id IN (%s) AND (%s) OR (%s) LIMIT %d", 
            [
                ['$in' => [1,3,4]],
                ['$and' => ['username' => 'ersin', 'surname' => 'guvenc']],
                ['$or' => ['a' => '%223%']],
            ]
        );
    
        // $this->db->query(
        //     "SELECT * FROM users WHERE user_id IN (%s) AND (%s) OR (%s)",
        //     function () {
        //         $this->in(array(1,3,4));
        //         $this->and(array('username' => 'ersin', 'surname' => 'guvenc'));
        //         $this->or(array('a' => '%223%'));
        //     }
        // );

        // print_r(array_values(array('$in' => array(1,3,4))));

        // $this->db->query("INSERT INTO users %s", array('$into' => $data));
        // $this->db->query("INSERT INTO users %s", array('$multi' => $data));
        // $this->db->query("REPLACE INTO users %s", array('$into' => $data));
        // $this->db->query("UPDATE users SET %s WHERE id = ?", array('$set' => $data), array(4));
        // $this->db->query("UPDATE users SET %s WHERE id = ?", array(), array(4));
        
        // $this->db->query("DELETE FROM users WHERE user_id = ?", array(), array(4));
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->c['view']->load(
            'hello_world',
            function () {
                $this->assign('title', 'Welcome to Obullo !');
            }
        );
    }
}

/* End of file hello_world.php */
/* Location: .controllers/widgets/tutorials/hello_world.php */