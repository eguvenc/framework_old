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
        $block = new Obullo\Blocks\Annotations\Filter($this->c);
        $block->before('csrf');
        $block->before('activity')->when(array('post','get'));
        $block->before('auth');
        $block->initFilters('before');
        $block->initFilters('after');

        $this->c->load('url');
        // $this->c->load('view');
    }

    /**
     * Index
     *
     * @filter->before(["auth", "sad"]);
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