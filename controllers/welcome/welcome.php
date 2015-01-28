<?php

namespace Welcome;

Class Welcome extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
        $this->c->load('view');

        $db = $this->c->load('service provider database', array('connection' => 'default'));
        // $db2 = $this->c->load('service provider database', array('connection' => 'betforyou'));

        $db->query('SELECT * FROM users');
        var_dump($db->resultArray());

        $db = $this->c->load(
            'service provider database',
            array(
                'dsn'      => 'mysql:host=localhost;port=;dbname=betforyou',
                'username' => 'root',
                'password' => '123',
                'options' => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                    \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                ]
            )
        );

        $db->query('SELECT * FROM users LIMIT 1');
        var_dump($db->resultArray());
        $db = $this->c->load(
            'service provider database',
            array(
                'username' => 'root',
                'password' => '123',
                'dsn'      => 'mysql:host=localhost;port=;dbname=betforyousystem',
                'options' => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
                    \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                ]
            )
        );

        $db->query('SELECT * FROM users LIMIT 2');
        var_dump($db->resultArray());
        // $db = $this->c->load('db', ['connection' => 'default']);
        // $db = $this->c->load('db', ['connection' => 'default']);
        // $db = $this->c->load('db', ['connection' => 'default']);
        // $db = $this->c->load('db');
        // $db = $this->c->load('db');
        // var_dump($db2);

        // $test = new \Obullo\Database\Pdo\Handler\Mysql(
        //     $this->c,
        //     array(
        //         'pdo.dsn' => 'mysql:dbname=test;host=127.0.0.1',
        //         'pdo.username' => 'root', // optional
        //         'pdo.password' => '123', // optional
        //         'pdo.options' => array( // optional
        //             \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
        //         )
        //     )
        // );
        // $test->query('SELECT * FROM users');
        
        // var_dump($test->resultArray());
    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {

        die;
        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !'
            ]
        );
    }
}

/* End of file welcome.php */
/* Location: .controllers/welcome/welcome.php */