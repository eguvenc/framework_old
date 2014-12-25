<?php

namespace Widgets\Tutorials;

/**
 * Hello Layout
 */
Class Hello_Layout extends \Controller
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
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'hello_layout',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('title', 'Hello Layout World !');
                $this->assign('footer',  $this->template('footer'));
                $this->layout('welcome');
            }
        );
    }
}

/* End of file hello_layout.php */
/* Location: .public/tutorials/controller/hello_layout.php */