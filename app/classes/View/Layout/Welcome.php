<?php

namespace View\Layout;

Trait Welcome
{
    /**
     * Setup welcome layout
     * 
     * @return void
     */
    public function useLayer()
    {
        $this->c->load('view');
        $this->c->load('layer');

        /**
         * Assign layout variables
         */
        $this->view->assign('header', $this->c['layer']->get('views/header'));
        $this->view->assign('footer', $this->c['layer']->get('views/footer'));
    }
}

// END Welcome class

/* End of file Welcome.php */
/* Location: .app/classes/View/Welcome.php */