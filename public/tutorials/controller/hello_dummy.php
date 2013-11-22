<?php

/**
 * $c hello_dummy 
 * Dummy test class for Hmvc
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('test', function($arg1, $arg2, $arg3){
	echo '<pre>Response: '.$arg1 .' - '.$arg2. ' - '.$arg3.'</pre>';
});

/* End of file hello_dummy.php */
/* Location: .public/tutorials/controller/hello_dummy.php */