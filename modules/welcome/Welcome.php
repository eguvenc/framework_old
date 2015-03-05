<?php

namespace Welcome;

Class Welcome extends \Controller
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
        // $this->cache = $this->c['service provider cache']->get(['driver' => 'redis', 'options' => array('serializer' => 'php')]);

        // $test = array(
        //     'cwh69hl5vq' => array
        //         (
        //             'id' => 1,
        //             'username' => 'user@example.com',
        //             'password' => '$2y$06$6k9aYbbOiVnqgvksFR4zXO.kNBTXFt3cl8xhvZLWj4Qi/IpkYXeP.',
        //             'remember_token' => 'WazTPwdhn8QKlpjJnJh6x3J8LQAfK9zz',
        //             'date' => 0,
        //             '__rememberMe' => 0,
        //             '__isTemporary' => 1,
        //             '__token' => 'ma7NZ0vxtFTEdWBL',
        //             '__time' => 1425380526,
        //             '__isAuthenticated' => 1,
        //             '__type' => 'Authorized',
        //             '__isVerified' => 1
        //         )
        // );

        // $this->cache->set('test', $test);
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