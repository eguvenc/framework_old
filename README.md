** WARNING!!! : OBULLO 2.0 NOT RELEASED YET WE WORKING ON IT.
PLEASE DO NOT DOWNLOAD, WAIT THE BETA VERSION.


Obullo-2.0 WILL HAVE THESE FEATURES:
=========================

- Extremely ligtweight, works with php 5.3.0
- OBM ( Obullo Package & Version Update Manager ) works like node.js npm.
- MVC, HMVC 
- ODM ( Object Database Modeling )
- Tasks and CLI
- Advanced Debugging from Command Line
- Log Monitoring from Command Line
- Extended Mongodb Support
- Derived from Codeigniter
- Php5 Namespaces & Autoloaders
- Programming enjoyable with Obullo !
        
        // logging
        log\me('debug', 'this is my first log');

        i\post('');  // Input Post
        i\get('');

        // Initializing a model
        $model = new Model\User(false);
        $model->save();

        // Initializing a package
        new Email\Smtp();
        $this->email->to();
        $this->email->send();

        or

        $email = new Email\Smtp(false);
        $email->init();

        // Sessions
        sess\start();
        sess\set('foo');
        sess\get('foo');
        
        // Views
        echo view\get('welcome');
        
        // loading database        
        new Database\Db;
        $results = $this->db->select()->get('table');
