<?php

namespace Tests\Captcha;

use RuntimeException;
use Obullo\Tests\TestController;

class ReCaptcha extends TestController
{
    /**
     * Constructor
     * 
     * @param object $container container
     */
    public function __construct($container)
    {
        $container->get("recaptcha");
    }

    /**
     * Set translator
     * 
     * @return void
     */
    public function setTranslator()
    {
        $this->recaptcha->setTranslator($this->container->get('translator'));

        $this->assertInstanceOf(
            'Obullo\Translation\Translator',
            $this->recaptcha->getTranslator(),
            "I expect that the translator object is instance of 'Obullo\Translation\Translator'."
        );
    }

    /**
     * Get translator
     * 
     * @return void
     */
    public function getTranslator()
    {
        $this->setTranslator();
    }

    /**
     * Set site key
     * 
     * @return void
     */
    public function setSiteKey()
    {
        $this->recaptcha->setSiteKey("testSiteKey123");

        $this->assertEqual("testSiteKey123", $this->recaptcha->getSiteKey(), "I expect that the value is 'testSiteKey123'");
    }

    /**
     * Set secret key
     * 
     * @return void
     */
    public function setSecretKey()
    {
        $this->recaptcha->setSecretKey("testSecretKey123");

        $this->assertEqual("testSecretKey123", $this->recaptcha->getSecretKey(), "I expect that the value is 'testSecretKey123'");
    }

    /**
     * Set api language
     * 
     * @return void
     */
    public function setLang()
    {
        $this->recaptcha->setLang("es");

        $this->assertEqual("es", $this->recaptcha->getLang(), "I expect that the value is 'es'");
    }

    /**
     * Set remote user ip address (optional)
     * 
     * @return void
     */
    public function setUserIp()
    {
        $this->recaptcha->setUserIp("127.0.0.1");

        $this->assertEqual("127.0.0.1", $this->recaptcha->getUserIp(), "I expect that the value is '127.0.0.1'");
    }

    /**
     * Get user ip
     * 
     * @return void
     */
    public function getUserIp()
    {
        $this->setUserIp();
    }

    /**
     * Get site key
     * 
     * @return void
     */
    public function getSiteKey()
    {
        $this->setSiteKey();
    }

    /**
     * Get secret key
     * 
     * @return void
     */
    public function getSecretKey()
    {
        $this->setSecretKey();
    }

    /**
     * Get api language
     * 
     * @return void
     */
    public function getLang()
    {
        $this->setLang();
    }

    /**
     * Get captcha input name
     * 
     * @return void name
     */
    public function getInputName()
    {        
        $params = $this->container->get('recaptcha.params');

        if ($this->assertArrayHasKey('name', $params['form']['input']['attributes'], "I expect that the input attributes array has 'name' key.")) {
            $this->assertNotEmpty($params['form']['input']['attributes']['name'], "I expect that the input attributes 'name' item is not empty.");
        }
    }

    /**
     * Get javascript link
     * 
     * @return void
     */
    public function printJs()
    {
        $js = $this->recaptcha->printJs();

        $this->assertRegExp("#<script.*>.*<\/script>#", $js, "I expect that the js link contains script tag.");
    }

    /**
     * Print captcha html
     * 
     * @return void html
     */
    public function printHtml()
    {
        $html = $this->recaptcha->printHtml();

        $pattern = '#<input name="recaptcha" id="recaptcha" type="text" value="1" .*\/><div class="g-recaptcha" data-sitekey=".*">.*<\/div>#';

        $this->assertRegExp($pattern, $html, "I expect that the html link contains google recaptcha tags.");
    }

    /**
     * Disabled
     * 
     * @return void
     */
    public function printRefreshButton()
    {
        $this->assertNull($this->recaptcha->printRefreshButton(), "I expect that the value is null");
    }

    /**
     * Validation captcha
     * 
     * @param string $code result
     * 
     * @return bool
     */
    public function result($code)
    {
        // $response = $this->sendVerifyRequest(
        //     array(
        //         'secret'   => $this->getSecretKey(),
        //         'response' => $code,
        //         'remoteip' => $this->getUserIp() // optional
        //     )
        // );
        // return $this->validateCode($response);
    }


}