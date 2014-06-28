<?php

/**
 * $c home
 * 
 * @var Controller
 */
$app = new Controller(
    function () use ($c) {

        $c->load('post');

        // if ($this->post['submit_step1'] OR $this->post['submit_step2']) {
        //     new Setup_Wizard;

        //     $this->setup_wizard->setCssFile('/assets/css/setup_wizard.css');
        //     $this->setup_wizard->setDatabaseConfigFile('/app/config/debug/database.php');
        //     $this->setup_wizard->setDatabasePath('/var/www/demo_blog/db.sql');
        //     $this->setup_wizard->setDatabaseTemplate('/app/templates/database.tpl');
        //     $this->setup_wizard->setDatabaseDriver('Pdo_Mysql');
            
        //     $this->setup_wizard->setTitle('Demo_Blog Setup Wizard - Database Connection');
        //     $this->setup_wizard->setSubTitle('Configuration');

        //     $this->setup_wizard->setInput('hostname', 'Hostname', 'required');
        //     $this->setup_wizard->setInput('username', 'Username', 'required');
        //     $this->setup_wizard->setInput('password', 'Password', 'required', '', ' id="password" ');
        //     $this->setup_wizard->setInput('sql_path', 'Sql Path', '', $this->setup_wizard->getDatabasePath(), ' disabled ');
            
        //     $this->setup_wizard->setDatabaseItem('hostname', $this->post->get('hostname'));
        //     $this->setup_wizard->setDatabaseItem('username', $this->post->get('username'));
        //     $this->setup_wizard->setDatabaseItem('password', $this->post->get('password'));
        //     $this->setup_wizard->setDatabaseItem('database', 'demo_blog');

        //     $this->setup_wizard->setNote('* Configure your database connection settings then click to install.');   
        //     $this->setup_wizard->setIni('installed', 1);
        //     $this->setup_wizard->setRedirectUrl('/home');
        //     $this->setup_wizard->run();
        // } else {
        //     $setup_ini = parse_ini_file(DATA .'cache'. DS .'setup_wizard.ini');

        //     if ($setup_ini['installed'] == 0) {
        //         new Setup_Wizard;

        //         $this->setup_wizard->setCssFile('/assets/css/setup_wizard.css');
        //         $this->setup_wizard->setTitle('Demo_Blog Setup Wizard - Requirements');
        //         $this->setup_wizard->setExtension(array('pdo', 'mcrypt'));
        //         $this->setup_wizard->setNote('* Please install above the requirements then click next. "Otherwise application will not work correctly."');
        //         $this->setup_wizard->run();
        //     }
        // }
        $c->load('url');
        $c->load('html');
        $c->load('view');
        $c->load('form');
        $c->load('private');
    }
);

$app->func(
    'index',
    function () {

        $this->logger->output = true;
        
        $r = $this->private->get('posts/get_all'); // get all post data

        $this->view->load(
            'home', 
            function () use ($r) {
                $this->assign('title', 'Welcome to home');
                $this->assign('posts', $r['results']);
                $this->getScheme();
            }
        );
    }
);

/* End of file home.php */
/* Location: .public/home/controller/home.php */