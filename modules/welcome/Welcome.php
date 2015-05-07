<?php

namespace Welcome;

class Welcome extends \Controller
{
    use \View\Layout\Base;

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];

        // $this->entry = new \Model\Entry;
        // print_r($this->entry->findAll());

        // $this->entry->delete(1);

        // $this->entry->title = 'Insert Test';
        // $this->entry->content = 'Hello World';
        // $this->entry->date = time();
        // $this->entry->insert();

        // $result = $this->db->transaction(
        //     function () {

        //         return $this->db->exec(
        //             sprintf(
        //                 "DELETE FROM userst WHERE id = %d",
        //                 4
        //             )
        //         );
        //     }
        // );

        // var_dump($result);



        // $this->entry = new \Model\Entry;

        // // $row = $this->db->query(sprintf("SELECT * FROM users LIMIT %d", 1))->resultArray();
        

        // return $this->db->exec(
        //     sprintf(
        //         "UPDATE entries SET title = %s, content = %s, date = %d WHERE entry_id = %d"
        //         $this->title,
        //         $this->content,
        //         $this->date,
        //         $id
        //     )
        // );


        // print_r($row);

    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        $this->view->load(
            'welcome',
            [
                'title' => 'Welcome to Obullo !',
            ]
        );
    }
}


/* End of file welcome.php */
/* Location: .modules/welcome/welcome.php */