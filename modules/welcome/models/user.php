<?php
namespace Model;
use Ob;

Class User extends Ob\Model // extends Ob\Odm
{
    function __construct()
    {
        // parent::__construct(new Schema\User());
        parent::__construct(); // db connection false.
    }
    
    function test()
    {
        print_r($this->db->get('users')->result_array());
        
        echo 'test';
    }
}

/* End of file User.php */
/* Location: .modules/welcome/models/user.php */