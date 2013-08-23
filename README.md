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

        // Initializing a model for modules\models directory
        $model = new Models\User(false);
        $model->save();

        // Email component ( library )
        new Email();
        $this->email->to();
        $this->email->send();

        or
        // Email component ( library )
        $email = new Email(false);
        $email->init();

        // Session component ( helper )
        new sess\start();
        sess\set('foo');
        sess\get('foo');

        // Cookie component ( helper )
        new cookie\start();
        cookie\set('key', 'value');
        cookie\get('key');

        // Vi component ( helper )
        echo vi\view('welcome'); 
        
        // Getting a view from modules\views directory
        echo vi\views('footer');

        // Loading database
        new Db();
        $this->db->get('table');
        $results = $this->db->resultArray();