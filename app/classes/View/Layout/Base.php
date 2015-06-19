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
        $this->view->assign(
            [
                'header' => $this->layer->get('views/header'),
                'footer' => $this->layer->get('views/footer')
            ]
        );
    }
}