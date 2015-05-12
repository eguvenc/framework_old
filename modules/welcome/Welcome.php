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
        $this->c['request'];

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