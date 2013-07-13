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
        Ob\log\me('debug', 'this is my first log');

        Ob\i\post('');  // Input Post
        Ob\i\get('');

        // Initializing a model
        $model = new Model\User;

        // Initializing a package
        $email = new Ob\Email();
        $email->init();

        // Sessions
        Ob\sess\start();
        Ob\sess\set('foo');
        Ob\sess\get('foo');
        
        // Views
        echo Ob\view\get('welcome');
        
        // loading database        
        new Ob\Database;
        
        $results = $this->db->select()->get('table');
