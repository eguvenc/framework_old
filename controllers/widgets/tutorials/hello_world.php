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
        // $this->c->bind('user', 'Member\User');
        
        $this->c->bind('model as user', 'Membership\User');
        // $this->c->bind('model as UserLogin', 'User');
        // $this->c->bind('model.user', 'User');
        // $this->c->bind('user', 'Member\User');

        $this->model->user->test();
        // $this->model->userLogin->test();

        // $this->c->bind('new model.user');
        // $this->c->bind('new model.user');
        // $this->c->bind('new model.user');
        //$this->c->bind('model.user');
        //$this->c->bind('model.user');
        // $this->c->bind('model.user');
        // $this->c->bind('model.user');
        // $this->c->bind('model.test');

        // var_dump($this->model->user);
        // var_dump(get_class($this->model));
        // var_dump(get_class($this->model->user));
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
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer'));
            }
        );
    }
}

/* End of file hello_world.php */
/* Location: .controllers/widgets/tutorials/hello_world.php */