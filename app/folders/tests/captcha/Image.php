<?php

namespace Tests\Captcha;

use Obullo\Tests\TestController;

class Image extends TestController
{
    /**
     * Get item
     * 
     * @return void
     */
    public function setTranslator()
    {
        $this->captcha->setTranslator($this->container->get('translator'));

        $this->assertInstanceOf(
            'Obullo\Translation\Translator',
            $this->captcha->getTranslator(),
            "I expect that the translator object is instance of 'Obullo\Translation\Translator'."
        );
    }

    /**
     * Set charset
     *
     * @return void
     */
    public function setCharset()
    {
        $this->captcha->setCharset('utf-16');
        $this->assertEqual($this->captcha->getProperty('charset'), 'UTF-16', "I expect that the value is 'UTF-16'.");
    }

    /**
     * Set input image element attributes
     * 
     * @return void
     */
    public function setImageAttributes()
    {
        $this->captcha->setImageAttributes(
            [
                'src'   =>  '/test/index/',
                'style' => 'display:block;',
                'id'    => 'test_image',
                'class' => 'test'
            ]
        );
        $attributes = $this->captcha->getProperty('params')['form']['img']['attributes'];

        if ($this->assertArrayHasKey('src', $attributes, "I expect that the attributes array has 'src' key.")) {
            $this->assertEqual($attributes['src'], "/test/index/", "I expect that the value of src is equal to '/test/index/'.");
        }
        if ($this->assertArrayHasKey('style', $attributes, "I expect that the attributes array has 'style' key.")) {
            $this->assertEqual($attributes['style'], "display:block;", "I expect that the value of src is equal to 'display:block;'.");
        }
        if ($this->assertArrayHasKey('id', $attributes, "I expect that the attributes array has 'id' key.")) {
            $this->assertEqual($attributes['id'], "test_image", "I expect that value of id is equal to 'test_image'.");
        }
        if ($this->assertArrayHasKey('class', $attributes, "I expect that the attributes array has 'class' key.")) {
            $this->assertEqual($attributes['class'], "test", "I expect that the value of class is equal to 'test'.");
        }
    }

    /**
     * Set captcha input element attributes
     * 
     * @return void
     */
    public function setInputAttributes()
    {
        $this->captcha->setInputAttributes(
            [
                'type'  => 'text',
                'name'  => 'test_answer',
                'class' => 'test-control',
                'id'    => 'test_answer'
            ]
        );
        $attributes = $this->captcha->getProperty('params')['form']['input']['attributes'];

        if ($this->assertArrayHasKey('type', $attributes, "I expect that the attributes array has 'type' key.")) {
            $this->assertEqual($attributes['type'], "text", "I expect that the value of src is equal to 'text'.");
        }
        if ($this->assertArrayHasKey('name', $attributes, "I expect that the attributes array has 'name' key.")) {
            $this->assertEqual($attributes['name'], "test_answer", "I expect that the value of name is equal to 'test_answer'.");
        }
        if ($this->assertArrayHasKey('class', $attributes, "I expect that the attributes array has 'class' key.")) {
            $this->assertEqual($attributes['class'], "test-control", "I expect that the value of class is equal to 'test-control'.");
        }
        if ($this->assertArrayHasKey('id', $attributes, "I expect that the attributes array has 'id' key.")) {
            $this->assertEqual($attributes['id'], "test_answer", "I expect that value of id is equal to 'test_answer'.");
        }
    }

    /**
     * Set refresh button html tag
     * 
     * @return void
     */
    public function setRefreshButton()
    {
        $this->captcha->setRefreshButton('<button type="button">Refresh</button>');
        $html = $this->captcha->getProperty('params')['form']['refresh']['button'];

        $this->assertEqual($html, '<button type="button">Refresh</button>', "I expect that the value contains refresh button.");
    }

    /**
     * Set background type
     * 
     * @return void
     */
    public function setBackground()
    {
        $this->captcha->setBackground('secure');
        
        $this->assertEqual($this->captcha->getProperty('params')['background'], 'secure', "I expect that the value contains refresh button.");
    }

    /**
     * Set background noise color
     * 
     * @return void
     */
    public function setNoiseColor()
    {
        $this->captcha->setNoiseColor('yellow');

        $this->assertEqual($this->captcha->getProperty('params')['image']['colors']['noise'][0], 'yellow', "I expect that the value is 'yellow'.");
    }

    /**
     * Set text color
     * 
     * @return void
     */
    public function setColor()
    {
        $this->captcha->setColor('green');

        $this->assertEqual($this->captcha->getProperty('params')['image']['colors']['text'][0], 'green', "I expect that the value is 'yellow'.");
    }

    /**
     * Set imagetruecolor() on / off
     * 
     * @return void
     */
    public function setTrueColor()
    {
        $this->captcha->setTrueColor(false);

        $this->assertFalse($this->captcha->getProperty('params')['image']['truecolor'], "I expect that the value is false.");
    }

    /**
     * Set text font size
     * 
     * @return void
     */
    public function setFontSize()
    {
        $this->captcha->setFontSize(28);

        $this->assertEqual($this->captcha->getProperty('params')['font']['size'], 28, "I expect that the value is 28.");
    }

    /**
     * Set image height
     * 
     * @return void
     */
    public function setHeight()
    {
        $this->captcha->setHeight(100);

        $this->assertEqual($this->captcha->getProperty('params')['image']['height'], 100, "I expect that the value is 100.");
    }

    /**
     * Set pool
     * 
     * @return void
     */
    public function setPool()
    {
        $this->captcha->setPool('alpha');
        
        $this->assertEqual($this->captcha->getProperty('params')['characters']['default']['pool'], 'alpha', "I expect that the value is 'alpha'.");
    }

    /**
     * Set character length
     * 
     * @return void
     */
    public function setChar()
    {
        $this->captcha->setChar(8);

        $this->assertEqual($this->captcha->getProperty('params')['characters']['length'], 8, "I expect that the value length is 8.");
    }

    /**
     * Set wave 
     * 
     * @return void
     */
    public function setWave()
    {        
        $this->captcha->setWave(true);

        $this->assertTrue($this->captcha->getProperty('params')['image']['wave'], "I expect that the value is true.");
    }

    /**
     * Set font
     * 
     * @return void
     */
    public function setFont()
    {
        $this->captcha->setFont(['AlphaSmoke','Anglican']);

        $this->assertArrayContains(['AlphaSmoke','Anglican'], $this->captcha->getFonts(), "I expect that the array has contains 'AlphaSmoke','Anglican' fonts.");
    }

    /**
     * Get fonts
     * 
     * @return array
     */
    public function getFonts()
    {
        $this->captcha->setFont(['AlphaSmoke','Anglican']);

        $this->assertArrayContains(['AlphaSmoke','Anglican'], $this->captcha->getFonts(), "I expect that the array has contains 'AlphaSmoke','Anglican' fonts.");
    }

    /**
     * Get captcha input name
     * 
     * @return string name
     */
    public function getInputName()
    {
        $this->captcha->setInputAttributes(
            [
                'type'  => 'text',
                'name'  => 'test_answer',
                'class' => 'test-control',
                'id'    => 'test_answer'
            ]
        );
        $this->assertEqual($this->captcha->getInputName(), 'test_answer', "I expect that the value is 'test_answer'.");
    }

    /**
     * Get captcha image url
     * 
     * @return string image asset url
     */
    public function getImageUrl()
    {
        $this->captcha->setImageAttributes(
            [
                'src'   =>  '/test/index/',
                'style' => 'display:block;',
                'id'    => 'test_image',
                'class' => 'test'
            ]
        );
        $this->captcha->init();
        $this->assertEqual($this->captcha->getImageUrl(), '/test/index/', "I expect that the value is '/test/index/'.");
    }

    /**
     * Get captcha Image UniqId
     * 
     * @return string 
     */
    public function getImageId()
    {
        $this->captcha->init();

        ob_start();
        $this->captcha->create();
        ob_get_clean();

        $this->assertEqual(strlen($this->captcha->getImageId()), 32, "I expect that the length of characters is equal to 32.");
    }

    /**
     * Get the current captcha code
     * 
     * @return string
     */
    public function getCode()
    {
        $code = $this->captcha->generateCode();

        $this->assertEqual($this->captcha->getCode(), $code, "I expect that the value is '$code'.");
    }

    /**
     * Create image captcha and save into captcha
     *
     * @return void
     */
    public function create()
    {
        $this->captcha->init();

        ob_start();
        $this->captcha->create();
        $binary = ob_get_clean();

        $this->assertNotEmpty($binary, "I expect that value is not empty. ");
    }

}