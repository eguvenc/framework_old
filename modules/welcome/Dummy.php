<?php

namespace Welcome;

Class Dummy extends \Controller
{
    /**
     * Index
     * 
     * @param string $arg1 test argument1
     * @param string $arg2 test argument2
     * @param string $arg3 test argument3
     * 
     * @return string
     */
    public function index($arg1 = '', $arg2 = '', $arg3 = '')
    {
        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Response: <span class="string">'.$arg1 .' - '.$arg2. ' - '.$arg3.'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->c['app']->uri->getUriString().'</span></pre>';
        echo '<p>-----------------------------------------</p>';

        echo $this->c['layer']->get('views/test');
        echo $this->c['layer']->get('views/test');
        
        echo 'WELCOME MODULE: '.$this->router->fetchNamespace().'<br>';

        echo $this->c['view']->get('dummy');  // In sub layers we need to use nested method to pass reference of
    }
}


/* End of file dummy.php */
/* Location: .controllers/tutorials/dummy.php */