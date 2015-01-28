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
        // $this->c->load('cache');

        // $this->cache->set('test', array('a', 'hello',  'myworld'));
        // $this->cache->setOption(array('serializer' => 'SERIALIZER_NONE'));
        // $this->cache->set('test2', array('a', 'hello',  'myworld'));


        $this->cache = $this->c->load('service provider cache', ['driver' => 'redis']);

        $this->cache->setOption(array('serializer' => 'SERIALIZER_PHP'));

        // $this->cache = $this->c->load(
        //     'service provider cache', 
        //     [
        //         'driver' => 'redis', 
        //         'options' => array(
        //             'serializer' => 'SERIALIZER_NONE'
        //         )
        //     ]
        // );


        // $this->c->load('cache');

        // var_dump($this->cache);

        //$this->mongo = $this->c->load('service provider mongo', ['connection' => 'default'])->selectDb('db');

        // FACTORY
        // $mongo = $this->c->load('service provider mongo', ['server' => 'mongodb://localhost:27017', 'options' => array('connect' => true)];  // factory
        
        
        // $this->mongo = $this->c->load('mongo', ['connection' => 'default'])->selectDb('db');

        // foreach ($this->mongo->logs->find() as $val) {
        //     echo $val['message'];
        // }

        // $this->mongo = $this->c->load('mongo', ['mongo.server' => 'default'])->selectDb('stats');  // factory

        // foreach ($this->mongo->users->find() as $val) {
        //     echo $val['id'];
        // }


        // $mongo = $this->c->load('service provider mongo', array('server' => 'default2'));  // factory
        
        // $this->mongo = $this->c['mongo.connection.default'];

        // $this->mongo = $mongo->selectDb('db');
        // $this->mongo->users->find();


        // $mongo->factory($server = "mongodb://localhost:27017", $options = array('connect' => true));


        // $this->mongo['default'];  // get default connection.

        // $this->c->load('new service/provider/mongo');
        // $this->


        // $this->db = $c->load(
        //             'return new service/provider/mongo',
        //             $this->databaseName
        //         );
        // $this->collection = $this->db->selectCollection($this->databaseName, $this->collectionName);

        // $this->mongo->database('db');
    
        // foreach ($this->mongo->get('logs')->resultArray() as $val) {
        //     echo $val['message'].'<br>';
        // }

        // foreach ($this->mongo->users->find() as $val) {
        //     echo $val['_id'].'<br>';
        // }

        // $this->c->load('new service provider mongo')->selectCollection('db', 'logs');

        // $this->mongo->selectDb('db')->logs;


        // // $this->c->extend('provider:mongo', );

        // foreach ($this->mongo->logs->find() as $val) {
        //     echo $val['message'].'<br>';
        // }

        // $this->mongo->get('logs');

        // // $docs = $this->mongo->logs->find();
        // foreach ($this->mongo->resultArray() as $val) {
        //     echo $val['message'].'<br>';
        // }

        // $this->c->load('service/mongo', array('db' => 'stats'));

        // $this->mongo->database('stats');
        // $this->mongo->get('users');

        // print_r($this->mongo->resultArray());

        // foreach ($this->mongo->resultArray() as $val) {
        //     echo $val['id'].'<br>';
        // }
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