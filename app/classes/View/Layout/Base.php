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
        $this->c['view']->assign(
            [
                'header' => $this->c['layer']->get('views/header'),
                'footer' => $this->c['layer']->get('views/footer')
            ]
        );
    }
}