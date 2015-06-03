<?php

namespace Service;

use Obullo\Service\ServiceInterface;
use Obullo\Form\Element as FormElement;
use Obullo\Container\ContainerInterface;

class Element implements ServiceInterface
{
    /**
     * Registry
     *
     * @param object $c container
     * 
     * @return void
     */
    public function register(ContainerInterface $c)
    {
        $c['element'] = function () use ($c) {
            return new FormElement($c);
        };
    }
}

// END Element service

/* End of file Element.php */
/* Location: .app/classes/Service/Element.php */