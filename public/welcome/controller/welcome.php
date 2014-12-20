<?php

Class Welcome extends Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('url');
    }

    /**
     * Index
     *
     * @filter->before("activity")->when("get", "post");
     * @filter->after("activity");
     * @filter->method("get");
     * 
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'welcome',
            function () {
                $this->assign('name', 'Obullo');
                $this->assign('footer', $this->template('footer', false));
            }
        );
    }
}


/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */