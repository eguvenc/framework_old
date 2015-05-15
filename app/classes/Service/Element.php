<?php

namespace Service;

use Obullo\Container\Container;
use Obullo\Form\Element as FormElement;
use Obullo\Service\ServiceInterface;

class Element implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(Container $c)
    {
        $c['element'] = function () use ($c) {
            return new FormElement($c);
        };
    }
}

// END Element service

/* End of file Element.php */
/* Location: .app/classes/Service/Element.php */