<?php

namespace View\Layout;

use Obullo\View\ViewController;

Trait Base
{
    /**
     * Setup layout & assign view variables
     * 
     * @return void
     */
    public function extend()
    {
        $layer = new ViewController;

        $this->c['view']->assign(
            [
                'header', $layer->get('views/header'),
                'footer', $layer->get('views/footer')
            ]
        );
    }
}

// END Base class

/* End of file Base.php */
/* Location: .app/classes/View/Layout/Base.php */