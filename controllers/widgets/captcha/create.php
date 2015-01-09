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
        $this->c->load('service/captcha');
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

        $this->captcha->image->setDriver('secure');  // or set to "cool" with no background
        $this->captcha->image->setPool('alpha');
        $this->captcha->image->setChar(5);
        $this->captcha->image->setWave(false);
        $this->captcha->image->setFont(array('NightSkK','Almontew', 'Fordd'));
        $this->captcha->image->setFontSize(39);
        $this->captcha->image->setHeight(98);
        $this->captcha->image->setColor(array('red','black','blue'));
        $this->captcha->image->setNoiseColor(array('red','black','blue'));

        $this->captcha->image->create();
    }
}

/* End of file create.php */
/* Location: .controllers/widgets/captcha/create.php */