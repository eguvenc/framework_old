<?php
defined('BASE') or exit('Access Denied!');

/**
 * @see http://obullo.com/user_guide/en/1.0.1/working-with-parent-controllers.html
 */

Class App_Controller extends Controller
{                                     
    public function __construct()
    {
        parent::__construct();
    }
      
}

/* End of file App_Controller.php */
/* Location: ./app/libraries/App_Controller.php */