<?php

/**
 * $app hello_dummy
 * 
 * Dummy test class
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('validator');
    }
);

$app->func(
    'index', 
    function () use ($c) {

        try {

            // Successfull Operation Array
            // $r = array(
            //   'success' => 1, 
            //   'message' => 'FORM_MESSAGE:SUCCESS',
            //   'errors'  => array(),
            //   'results' => array(),
            // );
            // echo json_encode($r);
            
            new Exception('Hello dummy exception.');

        } catch (Exception $e) {

            // Failed Operation Array
            $r = array(
              'success' => 0,     // status "1" or "0"
              'message' => 'FORM_MESSAGE:VALIDATION_ERROR',  // optional error message
              'errors'  => $this->validator->getErrors(),    // optional input field errors
              'results' => array(),    // optional query results
              'e' => $e->getMessage(), // optional exception message
            ); 
            echo json_encode($r);
        }
    }
);

/* End of file hello_dummy.php */
/* Location: .public/tutorials/controller/hello_dummy.php */