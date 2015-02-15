<?php

namespace Examples\Captcha;

Class Debug extends \Controller
{
    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['url'];
        $this->c['captcha/debug'];  // this->captcha->debug(); olmalı.
    }

    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        $fonts = $this->captchaDebug->font();

        $this->c['view']->load(
            'debug',
            function () use ($fonts) {
                $this->assign('fonts', $fonts);
                $this->assign('footer', $this->template('footer'));
            }
        );
    }
}

/* End of file debug.php */
/* Location: .controllers/examples/captcha/debug.php */