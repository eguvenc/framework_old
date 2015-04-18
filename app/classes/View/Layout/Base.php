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
    
        $this->view->assign('header', $this->c['layer']->get('views/header'));
        $this->view->assign('footer', $this->c['layer']->get('views/footer'));
    }
}

// END Base class

/* End of file Base.php */
/* Location: .app/classes/View/Layout/Base.php */