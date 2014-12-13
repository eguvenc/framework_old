<?php

/**
 * $app hello_world
 * 
 * @var Controller
 */ 


Class Hello_World extends Controller {

     /*
     * [index description]
     * @return [type] [description]
     */
     
    public function index()
    {
        echo 'ok';
    }
}

// $app = new Controller(
//     function ($c) {
//         $c->load('view');
//     }
// );

// $app->func(
//     'index',
//     function () {

//         $this->view->load(
//             'hello_world',
//             function () {
//                 $this->assign('name', 'Obullo');
//                 $this->assign('footer', $this->template('footer'));
//             }
//         );

//     }
// );

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */