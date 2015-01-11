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

        // $this->user = new Model\Membership\User;

        $this->c->bind('model user', 'Membership\User');

        $this->model->user->test();

        // echo get_class($this->model->member).'<br>';

        // $this->model->member->test();
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