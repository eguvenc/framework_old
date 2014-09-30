<?php

/**
 * Captcha Class
 *
 * @package       packages
 * @subpackage    captcha
 * @category      captcha
 * @link
 */
Class Captcha
{    
    public $driver;                  // Driver type
    public $captcha_id;              // captcha_id 
    public $colors;                  // Defined system colors
    public $default_text_color;      // Captcha text color
    public $default_noise_color;     // Background noise property 
    public $wave_image;              // Font wave switch (bool)
    public $default_fonts;           // Actual keys of the fonts
    public $fonts;                   // Actual fonts
    public $debugFlag = 'random';   // Font debug flag
    public $img_url;                 // URL for accessing images
    public $set_pool;                // Pool
    public $char_pools;              // Letters & numbers pool
    public $img_path;                // Image dir
    public $image_type = 'png';      // Image suffix (including dot)
    public $width;                   // Image width
    public $height;                  // Image height
    public $font_size;               // Font size
    public $char;                   // Number of lines on image
    public $del_rand = 10;          // Random delete number frequency
    public $expiration;              // How long to keep generated images
    public $sessionKey;              // Random session key for saving captcha code.
    public $imageUrl;                // Captcha image display url with base url
    public $send_output_header = false; // Whether to create captcha at browser header

    // ------------------------------------------------------------------------
    
    private $Yperiod    = 12;         // Wave Y axis
    private $Yamplitude = 14;         // Wave Y amplitude
    private $Xperiod    = 11;         // Wave X axis
    private $Xamplitude = 5;          // Wave Y amplitude
    private $scale      = 2;          // Wave default scale
    private $image;                     // Gd image content
    private $code;                      // Generated image code

    /**
     * Constructor
     * 
     * @param array $config
     */
    public function __construct($config = array())
    {
        global $packages, $logger;

        $this->config = getConfig('captcha'); // config->captcha.php config
        $sess = $this->config['sess']();      // get session dependency
        $this->sess = $sess::$driver;         // run dependecy class
        $this->init();
        
        $this->img_path          = ROOT . str_replace('/', DS, trim($this->config['img_path'], '/')) . DS;  // replace with DS
        $this->img_url           = getInstance()->uri->getBaseUrl($this->config['img_path'] . DS); // add Directory Seperator ( DS )
        $this->user_font_path    = ROOT . $this->config['user_font_path'] . DS;
        $this->default_font_path = PACKAGES . 'captcha' . DS . 'releases' . DS . $packages['dependencies']['captcha']['version'] . DS . 'src' . DS . 'fonts' . DS;

        if ( ! isset(getInstance()->captcha)) {
            getInstance()->captcha = $this; // Make available it in the controller $this->captcha->method();
        }

        $this->gc(); // run garbage collection

        $logger->debug('Captcha Class Initialized');
    }

    // ------------------------------------------------------------------------

    /**
     * Initialize to Default Settings 
     * 
     * @return void
     */
    private function init()
    {
        $this->driver              = $this->config['driver'];
        $this->captcha_id          = $this->config['captcha_id'];
        $this->colors              = $this->config['colors'];
        $this->default_text_color  = $this->config['default_text_color'];
        $this->default_noise_color = $this->config['default_noise_color'];
        $this->default_fonts       = array_keys($this->config['fonts']);
        $this->fonts               = $this->config['fonts'];
        $this->expiration          = $this->config['expiration'];
        $this->char                = $this->config['char'];
        $this->height              = $this->config['height'];
        $this->font_size           = $this->config['font_size'];
        $this->set_pool            = $this->config['set_pool'];
        $this->wave_image          = $this->config['wave_image'];
        $this->char_pool           = $this->config['char_pool'];
        $this->image_type          = $this->config['image_type'];
        $this->send_output_header  = $this->config['send_output_header'];
    }

    // ------------------------------------------------------------------------

    /**
     * Set driver type
     * 
     * @param string $driver
     * @return object
     */
    public function setDriver($driver = 'cool')
    {
        if ($driver == 'secure' OR $driver == 'cool') {
            $this->driver = $driver;
        }
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Set default variables
     * 
     * @param string $variable variable name
     * @param string $defaultVariable default variable name
     * @param string | array $values
     */
    private function _setDefaults($variable, $defaultVariable, $values)
    {
        $array = array();
        if (is_string($values)) {
            $values = array($values);
        }
        foreach ($values as $val) {
            if (array_key_exists($val, $this->$variable)) {
                $array[$val] = $val;
            }
        }
        if ( ! empty($array)) {
            $this->{$defaultVariable} = $array;
        }
        unset($array);
    }

    // ------------------------------------------------------------------------
    
    /**
     * Set capthca id
     * 
     * @param string captcha id
     * 
     * @return void
     */
    public function setCaptchaId($captchaId)
    {
        $this->captcha_id = $captchaId;
    }

    // ------------------------------------------------------------------------

    /**
     * Set background noise color
     * 
     * @param mixed $values 
     * 
     * @return object
     */
    public function setNoiseColor($values = '')
    {
        if (empty($values)) {
            return $this;
        }
        $this->_setDefaults('colors', 'default_noise_color', $values);
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Set text color
     * 
     * @param mixed $values
     * @return object
     */
    public function setColor($values)
    {
        if (empty($values)) {
            return $this;
        }
        $this->_setDefaults('colors', 'default_text_color', $values);
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Set font type
     * 
     * @param mixed $values
     * @return object
     */
    public function setFont($values)
    {
        if (empty($values)) {
            return $this;
        }
        $this->_setDefaults('fonts', 'default_fonts', $values);
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Set text font size
     * 
     * @param int $font_size
     * @return object
     */
    public function setFontSize($font_size)
    {
        $this->font_size = $font_size;
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Set image height
     * 
     * @param int $height
     * @return object
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Set pool
     * 
     * @param string $pool
     * @return object
     */
    public function setPool($pool)
    {
        if (array_key_exists($pool, $this->char_pool)) {
            $this->set_pool = $pool;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Set character
     * 
     * @param int $char
     * @return object
     */
    public function setChar($char)
    {
        $this->char = $char;
    }

    // ------------------------------------------------------------------------

    /**
     * Set wave TRUE or FALSE
     * 
     * @param option $Wave
     * @return object
     */
    public function setWave($wave)
    {
        $this->wave_image = $wave;
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Clear variables
     * 
     * @return void
     */
    public function clear()
    {
        $this->init();
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Generate image code
     * 
     * @return void
     */
    private function generateCode()
    {
        global $config;

        if ($this->debugFlag == 'random') {
            $possible = $this->char_pool[$this->set_pool];
            $this->code = '';
            $i = 0;
            while ($i < $this->char) {
                $this->code.= mb_substr($possible, mt_rand(0, mb_strlen($possible, $config['charset']) - 1), 1, $config['charset']);
                $i++;
            }
        } elseif ($this->debugFlag == 'all') {

            $this->code = $this->char_pool['random'];
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Create image captcha ans save into
     * captcha
     *
     * @return void
     */
    public function create()
    {
        $this->generateCode();  // generate captcha code

        $key_rand  = array_rand($this->default_fonts);
        $font_path = $this->default_font_path . $this->fonts[$this->default_fonts[$key_rand]];

        if (strpos($this->default_fonts[$key_rand], '.ttf')) {
            $font_path = $this->user_font_path . $this->fonts[$this->default_fonts[$key_rand]];
        }

        $key_rand = array_rand($this->default_text_color);
        $default_text_color = $this->default_text_color[$key_rand];
        $key_rand = array_rand($this->default_noise_color);
        $default_noise_color = $this->default_noise_color[$key_rand];
        $this->width = (($this->height / $this->font_size) + $this->char) * 25;

        $this->image = imagecreate($this->width, $this->height) or die('Cannot initialize new GD image stream');
        imagecolorallocate($this->image, 255, 255, 255);

        $color_explode = explode(',', $this->colors[$default_text_color]);
        $text_color = imagecolorallocate($this->image, $color_explode['0'], $color_explode['1'], $color_explode['2']);
        $color_explode = explode(',', $this->colors[$default_noise_color]);
        $noise_color = imagecolorallocate($this->image, $color_explode['0'], $color_explode['1'], $color_explode['2']);

        if ($this->driver != 'cool') {
            $w_h_value = $this->width / $this->height;
            $w_h_value = $this->height * $w_h_value;
            for ($i = 0; $i < $w_h_value; $i++) {
                imagefilledellipse($this->image, mt_rand(0, $this->width), mt_rand(0, $this->height), 1, 1, $noise_color);
            }
        }

        $textbox = imagettfbbox($this->font_size, 0, $font_path, $this->code) or die('Error in imagettfbbox function');
        $x = ($this->width - $textbox[4]) / 2;
        $y = ($this->height - $textbox[5]) / 2;

        $this->sessionKey = md5($this->sess->get('session_id') . uniqid(time()));

        $imgName = $this->sessionKey . '.' . $this->image_type;
        $this->imageUrl = $this->img_url . $imgName;

        imagettftext($this->image, $this->font_size, 0, $x, $y, $text_color, $font_path, $this->code) or die('Error in imagettftext function');

        if ($this->wave_image) {
            $this->waveImage();
        }

        if ($this->driver != 'cool') {
            $w_h_value = $this->width / $this->height;
            $w_h_value = $w_h_value / 2;
            for ($i = 0; $i < $w_h_value; $i++) {
                imageline($this->image, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $noise_color);
            }
        }

        if ($this->send_output_header) {
            header('Content-Type: image/png');
            imagepng($this->image);
            imagedestroy($this->image);
        } else {
            imagepng($this->image, $this->img_path . $imgName);
            imagedestroy($this->image);
        }
        $this->sess->set($this->captcha_id, array('image_name' => $this->sessionKey, 'code' => $this->code));
    }

    // ------------------------------------------------------------------------

    /**
     * Get captcha image url
     * 
     * @return string image asset url
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    // ------------------------------------------------------------------------

    /**
     * Get captcha Image UniqId
     * 
     * @return string 
     */
    public function getImageId()
    {
        return $this->sessionKey;
    }

    // ------------------------------------------------------------------------

    /**
     * Set wave for captcha image
     * 
     * @return void
     */
    private function waveImage()
    {
        $xp = $this->scale * $this->Xperiod * rand(1, 3); // X-axis wave generation
        $k = rand(0, 10);
        for ($i = 0; $i < ($this->width * $this->scale); $i++) {
            imagecopy($this->image, $this->image, $i - 1, sin($k + $i / $xp) * ($this->scale * $this->Xamplitude), $i, 0, 1, $this->height * $this->scale);
        }

        $k = rand(0, 10);              // Y-axis wave generation
        $yp = $this->scale * $this->Yperiod * rand(1, 2);
        for ($i = 0; $i < ($this->height * $this->scale); $i++) {
            imagecopy($this->image, $this->image, sin($k + $i / $yp) * ($this->scale * $this->Yamplitude), $i - 1, 0, $i, $this->width * $this->scale, 1);
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Garbage Collection
     * Remove old files from image directory
     * 
     * @return void
     */
    public function gc()
    {
        if ($this->send_output_header) {
            return;
        }
        if (mt_rand(1, $this->del_rand) !== 1) {  // don't do delete operation every time 
            return;
        }
        global $config;
        $expire = time() - $this->expiration;

        if ( ! $this->img_path OR mb_strlen($this->img_path, $config['charset']) < 2) {  // safety guard
            return; 
        }
        foreach (new DirectoryIterator($this->img_path) as $file) {
            if ( ! $file->isDot() AND ! $file->isDir()) {
                if (file_exists($file->getPathname()) AND $file->getMTime() < $expire) {
                    unlink($file->getPathname());
                }
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * [check description]
     * 
     * @param [type] $code [description]
     * 
     * @return [type]      [description]
     */
    public function check($code)
    {
        if ($this->sess->get($this->captcha_id)) {
            $captcha_value = $this->sess->get($this->captcha_id);
            if ($code == $captcha_value['code']) {
                if ($this->send_output_header == false) {
                    unlink($this->img_path . $captcha_value['image_name'] . '.' . $this->image_type);
                }
                $this->sess->remove($this->captcha_id);
                return true;
            }
            return false;
        }
        return false;
    }

    // ------------------------------------------------------------------------

    /**
     * Exclude fonts you don't want
     * 
     * @param mixed $values fonts
     * 
     * @return object
     */
    public function excludeFont($values)
    {
        if ( ! is_array($values)) {
            $values = array($values);
        }
        $this->default_fonts = array_diff($this->default_fonts, $values);
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Send Output Header
     * 
     * @return void
     */
    public function sendOutputHeader()
    {
        $this->send_output_header = true;
    }

    // ------------------------------------------------------------------------

    /**
     * Do test for all fonts
     * 
     * @return string Html output
     */
    public function fontTest()
    {
        $quicktest = new Captcha\Src\Captcha_Quicktest();
        return $quicktest->fontTest();
    }

    // ------------------------------------------------------------------------

    /**
     * Do test all variables
     * 
     * @return string Html output
     */
    public function varTest()
    {
        $quicktest = new Captcha\Src\Captcha_Quicktest();
        return $quicktest->variableTest();
    }

}

/* End of file captcha.php */
/* Location: ./packages/captcha/releases/0.0.1/captcha.php */