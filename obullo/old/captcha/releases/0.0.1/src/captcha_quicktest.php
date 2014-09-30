<?php
namespace Captcha\Src;

/**
 * Captcha Quicktest Class
 *
 * @package       packages
 * @subpackage    captcha
 * @category      test
 * @link
 */

Class Captcha_Quicktest {

	// ------------------------------------------------------------------------

	/**
	 * Do a quick test for each fonts.
	 * 
	 * @return array
	 */
	public function fontTest()
	{
        global $config;

        $images = '';
        $captcha = getInstance()->captcha;

        asort($captcha->fonts);

        foreach($captcha->fonts as $key => $value)
        {
        	$captcha->setHeight('50');
        	$captcha->setFontSize('25');
        	$captcha->setChar(mb_strlen($captcha->char_pool['random'], $config['charset']));
        	$captcha->setColor('cyan');
        	$captcha->setNoiseColor('cyan');
        	$captcha->setFont($key);
        	$captcha->create();

        	$images.= '<p>'.$key.'<img src="'.$captcha->getImageUrl().'"></p>';
        }

        return $images;
	}

	// ------------------------------------------------------------------------

	/**
	 * Do a quick test for each variables.
	 * 
	 * @return string
	 */
	public function variableTest()
	{	
        $captcha = getInstance()->captcha;
		$output = '';

      	$captcha->clear();
        $captcha->setDriver('secure');
        $captcha->create();

        $output.= '<p>setDriver: Secure </p> <img src="'.$captcha->getImageUrl().'">';

        $captcha->clear();
        $captcha->setDriver('cool"');
        $captcha->create();
        
        $output.= '<p>setDriver: Cool </p> <img src="'.$captcha->getImageUrl().'">';
        
        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setPool('alpha');
        $captcha->create();

        $output.= '<p>setPool: alpha </p> <img src="'.$captcha->getImageUrl().'">';
       
        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setPool('numbers');
        $captcha->create();

        $output.= '<p>setPool: numbers </p> <img src="'.$captcha->getImageUrl().'">';
       
        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setPool('random');
        $captcha->create();

        $output.= '<p>setPool: random </p> <img src="'.$captcha->getImageUrl().'">';
       
        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setChar(5);
        $captcha->create();

        $output.= '<p>setChar: 5 </p> <img src="'.$captcha->getImageUrl().'">';
         
        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setFontSize(30);
        $captcha->setHeight(80);
        $captcha->create();

        $output.= '<p>setFontSize: 30 </p> <img src="'.$captcha->getImageUrl().'">';
       
        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setFontSize(30);
        $captcha->setHeight(100);
        $captcha->create();

        $output.= '<p>setHeight: 80 </p> <img src="'.$captcha->getImageUrl().'">';
       
        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setWave(false);
        $captcha->create();

        $output.= '<p>setWave: false </p> <img src="'.$captcha->getImageUrl().'">';

        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setColor(array('red','black','blue'));
        $captcha->create();
        
        $output.= '<p>setColor: red-black-blue <p> <img src="'.$captcha->getImageUrl().'">';
        
        $captcha->clear();
        $captcha->setDriver('secure');
        $captcha->setNoiseColor(array('red','black','blue'));
        $captcha->create();

        $output.= '<p>setNoiseColor: red-black-blue </p> <img src="'.$captcha->getImageUrl().'">';
    
        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->setFont(array('AlphaSmoke','Bknuckss'));
        $captcha->create();
        
        $output.= '<p>setFont: AlphaSmoke,Bknuckss </p> <img src="'.$captcha->getImageUrl().'">';

        $captcha->clear();
        $captcha->setDriver('cool');
        $captcha->excludeFont(array('Bknuckss'));
        $captcha->create();

        $output.= '<p>excludeFont: Bknuckss </p> <img src="'.$captcha->getImageUrl().'">';

        return $output;
    }

}

/* End of file captcha_quicktest.php */
/* Location: ./packages/captcha/releases/0.0.1/captcha_quicktest.php */