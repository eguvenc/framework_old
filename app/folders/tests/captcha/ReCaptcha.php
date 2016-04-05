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
     * @return void
     */
    public function result()
    {
        $token = "03AHJ_Vuux6CMtsAQzSXgMk9aLhOVsGVFu3C0fRpKFnpcz1eWFJwEZMT5DmxhckYkjmFN1X7btUtxvGahdFKpmxAN1jIJ0Fo9_LUjHCGA_-A3FjBj-X2E7R7JY44y88riwcbDiihBzWYJE0dk3JuSHQRjT0MFcLDrBlxBwJiXRj-rJ76UqJRQcAfOXqqvO_Wy0BvbdrXrrRfrIG-QYVDFhaiquk8BAfd-wDO0GN76PsWvcdGKIZoTiIQwtrHIrtDQ77jEtJeK7tYDZP0u9rm2AhI1utIEcW09mLUFKbq8u5Uz5BOmD_Q82CqDD-Lcrlmkcvpkdon0E4iqZt5TEPkMCDjxTPxdVNNDdGeTGFq1UiXfUVSPFAvFgutTd4gAkCUWcKlLi55uI9zMKTxFCCVQ1dh4R5HZKhdVxxSsCcScDKdNddspTQ4xCO6AEUjWshknou8hnZ5l4KakR5sHEscnaaXg4SUk6Xp5ATYCrU5hF7a0Gr6eGATF_xiKKcU65Js1BXqm5PrQVxsg8R-nq8eCJjWIQ3rVK6ZF-eZk22ozwDhGbrQxaJmioyU4lckAYSvzmCFhOhkefXbAVcYj-gCWi_cWCnHGHp6zmwaYBepzFy3G-bdlWPUabTT02D2F8bQG6b9xN61RpP3k0Yyh3o1c6UHGfBNZBfyopqx6d_7BHUvNf59aAYjKs8OIiQxQ_PNgDoZIxsuXMwBsbQ0GChjsqLahvAJ_TlTrFOYr2-SaGt1Dv6bpDSZ7E8NnKypVK6QtoI3sDkUmHbdxPWcudeP0RuNmEWboeOKEZlekjL-ZwFpY8LKsSPzJSGyR53514C6X0MQ3KUolGopi3mrGUwjHAguSNQG0eC0w2IX1WejN2n3FFT1vk1HabaRpFybudnQGKV7x0XLBw6ELIfHyQrJYqaSAeNj_y6q-0mQ";

        $captchaResult = $this->recaptcha->result($token);

        $this->assertInstanceOf("Obullo\Captcha\CaptchaResult", $captchaResult, "I expect that the result is instance of 'Obullo\Captcha\CaptchaResult' class.");
        $this->assertEqual($captchaResult::FAILURE_CAPTCHA_NOT_FOUND, $captchaResult->getCode(), "I expect that the value is ".$captchaResult::FAILURE_CAPTCHA_NOT_FOUND.".");
    }


}