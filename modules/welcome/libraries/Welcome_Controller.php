<?php
defined('BASE') or exit('Access Denied!');

/**
 * @see http://obullo.com/user_guide/en/1.0.1/working-with-parent-controllers.html
 */

Class Welcome_Controller extends Controller
{                                     
    public function __construct()
    {
        parent::__construct();
    }
      
}

/* End of file Welcome_Controller.php */
/* Location: ./modules/welcome/parents/Welcome_Controller.php */