<?php

namespace Views;

use Obullo\Http\Controller;

class Footer extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {        
        echo $this->view->get(
            'footer',
            [
                'footer' => '<div class="layer example-gray">
                    Footer Controller <div class="layer example-white">$this->layer->get("views/footer")</div>
                </div>'
            ]
        );
    }
}