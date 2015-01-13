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
        $this->c->load('service/rbac', $this->c->load('service/db'));
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        echo '<pre>';
        $this->rbac->user->setUserId(1);
        $this->rbac->user->setResourceId('test');
        $this->rbac->user->setRoleIds(1);
        var_dump($this->rbac->user->object->form->getPermissions(array('input1', 'input2'), 'view'));

        $this->view->load(
            'welcome',
            function () {
                $this->assign('title', 'Welcome to Obullo !');
            }
        );
    }
}

/* End of file welcome.php */
/* Location: .controllers/welcome/welcome.php */