<?php

namespace View;

Trait Base
{
    /**
     * Setup layouts / Assign view variables
     * 
     * @return void
     */
    public function __invoke()
    {
        $this->view->withData(
            [
                'header' => $this->layer->get('views/header'),
                'footer' => $this->layer->get('views/footer')
            ]
        );
    }
}