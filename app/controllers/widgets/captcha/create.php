<?php

namespace Widgets\Captcha;

Class Create extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c->load('captcha');
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

        $this->captcha->setDriver('secure');  // or set to "cool" with no background
        $this->captcha->setPool('alpha');
        $this->captcha->setChar(5);
        $this->captcha->setWave(false);
        $this->captcha->setFont(array('NightSkK','Almontew', 'Fordd'));
        $this->captcha->setFontSize(39);
        $this->captcha->setHeight(98);
        $this->captcha->setColor(array('red','black','blue'));
        $this->captcha->setNoiseColor(array('red','black','blue'));

        $this->captcha->create();
    }
}

/* End of file create.php */
/* Location: .controllers/widgets/captcha/create.php */