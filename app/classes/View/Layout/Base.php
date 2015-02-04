<?php

namespace View\Layout;

Trait Base
{
    /**
     * Setup layout & assign view variables
     * 
     * @return void
     */
    public function extend()
    {
        $this->c['view'];
        $this->c['layer'];

        $this->view->assign('header', $this->layer->get('views/header'));
        $this->view->assign('footer', $this->layer->get('views/footer'));
    }
}

// END Base class

/* End of file Base.php */
/* Location: .app/classes/View/Layout/Base.php */