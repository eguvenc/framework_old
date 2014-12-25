<?php

namespace Jsons;

/**
 * Dummy controller
 */
Class Dummy extends Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('validator');        
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        try {

            // $r = array(
            //   'success' => 1, 
            //   'message' => 'FORM_MESSAGE:SUCCESS',
            //   'errors'  => array(),
            //   'results' => array(),
            // );
            // echo json_encode($r);

        } catch (Exception $e) {

            $r = array(
              'success' => 0,
              'message' => 'FORM_MESSAGE:VALIDATION_ERROR',  // optional error message
              'errors'  => $this->validator->getErrors(),    // optional input field errors
              'results' => array(),    // optional query results
              'e' => $e->getMessage(), // optional exception message
            );
            
            echo json_encode($r);
        }
    }
}

/* End of file dummy_json.php */
/* Location: .public/jsons/controller/dummy_json.php */