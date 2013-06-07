<?php
defined('BASE') or exit('Access Denied!'); 
/**
 * Obullo Framework (c) 2009.
 *
 * PHP5 HMVC Based Scalable Software.
 *
 * @package         obullo       
 * @author          obullo.com
 * @license         public
 * @since           Version 1.0
 * @filesource
 * @license
 */

// ------------------------------------------------------------------------

/**
 * Obullo Captcha Helper
 *
 * @package     Obullo
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Obullo Team
 * @link        
 */

/**
* Convert HEX Colors to RGB
* 
* @access   public
* @param    string
* @return   array
*/
if( ! function_exists('html2rgb') ) 
{
    function html2rgb($color)
    {
        if ($color[0] == '#')
        {
            $color = substr($color, 1);
        }
            
        if (strlen($color) == 6)
        {
            list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
        }
        elseif (strlen($color) == 3)
        {
            list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        }
        else
        {
            return FALSE;
        }

        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

        return array($r, $g, $b);
    }
}

// ------------------------------------------------------------------------

/**
* Create Captcha Image
* 
* @access   public
* @param    array  config data
* @param    string captcha image path
* @param    string captcha image url
* @param    string font path
* @return   array
*/
if( ! function_exists('captcha_create') ) 
{
    function captcha_create($data = '', $img_path = '', $img_url = '', $font_path = '')
    {        
        $defaults = array(           // Obullo changes ...
        'word'      => '',
        'max_char'  => '6',
        'img_path'  => '',
        'img_url'   => '',
        'img_width' => '150',
        'img_height'=> '30',
        'font_path' => '',
        'font_size' => '16',
        'expiration'=> 7200,
         // colors 
        'background' => '#FFFFFF',
        'border'     => '#CCCCCC',
        'text'       => '#330099',
        'grid'       => '#0033CC',
        'shadow'     => '#330099',
        // pool
        'pool'       => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'points'     => '32',
        'circles'    => '20',
        'radius'     => '16'
        );        
        
        foreach ($defaults as $key => $val)
        {
            if ( ! is_array($data))
            {
                if ( ! isset($$key) OR $$key == '')
                {
                    $$key = $val;
                }
            }
            else
            {            
                $$key = ( ! isset($data[$key])) ? $val : $data[$key];
            }
        }
        
        if ($img_path == '' OR $img_url == '')
        {
            return FALSE;
        }

        if ( ! @is_dir($img_path))
        {
            throw new Exception('Captcha path seems is not a directory !');
        }
        
        if ( ! is_really_writable(rtrim($img_path, DS). DS .'index.html'))
        {
            throw new Exception('Captcha path '.$img_path. ' is not writeable, please allow the write permission to it !');
        }
                
        if ( ! extension_loaded('gd'))
        {
            throw new Exception('Gd extension not found for captcha !');
        }        
        
        // -----------------------------------
        // Remove old images    
        // -----------------------------------
                
        list($usec, $sec) = explode(" ", microtime());
        $now = ((float)$usec + (float)$sec);
                
        $current_dir = @opendir($img_path);
        
        while($filename = @readdir($current_dir))
        {
            if ($filename != "." AND $filename != ".." AND $filename != "index.html" AND $filename != ".svn" AND $filename != ".git")
            {
                $name = str_replace(".jpg", "", $filename);
            
                if (($name + $expiration) < $now)
                {
                    @unlink($img_path.$filename);
                }
            }
        }
        
        @closedir($current_dir);

        // -----------------------------------
        // Do we have a "word" yet?
        // -----------------------------------
        
       if ($word == '')
       {
            $str = '';
            for ($i = 0; $i < $max_char; $i++)
            {
                $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
            }
            
            $word = $str;
       }
        
        // -----------------------------------
        // Determine angle and position    
        // -----------------------------------
        
        $length  = strlen($word);
        $angle   = ($length >= 6) ? rand(-($length-6), ($length-6)) : 0;
        $x_axis  = rand(6, (360/$length)-16);            
        $y_axis  = ($angle >= 0 ) ? rand($img_height, $img_width) : rand(6, $img_height);
        
        // -----------------------------------
        // Create image
        // -----------------------------------
                
        // PHP.net recommends imagecreatetruecolor(), but it isn't always available
        if (function_exists('imagecreatetruecolor'))
        {
            $im = imagecreatetruecolor($img_width, $img_height);
        }
        else
        {
            $im = imagecreate($img_width, $img_height);
        }
                
        // -----------------------------------
        //  Assign colors
        // -----------------------------------
        
        $bg  = html2rgb($background);
        $bg_color        = imagecolorallocate ($im, $bg[0], $bg[1], $bg[2]);
        $brd = html2rgb($border);
        $border_color    = imagecolorallocate ($im, $brd[0], $brd[1], $brd[2]);
        $txt = html2rgb($text);
        $text_color      = imagecolorallocate ($im, $txt[0], $txt[1], $txt[2]);
        $grd = html2rgb($grid);
        $grid_color      = imagecolorallocate($im, $grd[0],$grd[1],$grd[2]);
        $shd = html2rgb($shadow); 
        $shadow_color    = imagecolorallocate($im, $shd[0], $shd[1], $shd[2]);

        // -----------------------------------
        //  Create the rectangle
        // -----------------------------------
        
        ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $bg_color);
        
        // -----------------------------------
        //  Create the spiral pattern
        // -----------------------------------
        
        $theta      = 1;
        $thetac     = 7;
        $radius     = $radius;
        $circles    = $circles;
        $points     = $points;

        for ($i = 0; $i < ($circles * $points) - 1; $i++)
        {
            $theta = $theta + $thetac;
            $rad = $radius * ($i / $points );
            $x = ($rad * cos($theta)) + $x_axis;
            $y = ($rad * sin($theta)) + $y_axis;
            $theta = $theta + $thetac;
            $rad1 = $radius * (($i + 1) / $points);
            $x1 = ($rad1 * cos($theta)) + $x_axis;
            $y1 = ($rad1 * sin($theta )) + $y_axis;
            imageline($im, $x, $y, $x1, $y1, $grid_color);
            $theta = $theta - $thetac;
        }

        // -----------------------------------
        //  Write the text
        // -----------------------------------
        
        $use_font = ($font_path != '' AND file_exists($font_path) AND function_exists('imagettftext')) ? TRUE : FALSE;
            
        if ($use_font == FALSE)
        {
            // $font_size = 5;
            $x = rand(0, $img_width/($length/3));
            $y = 0;
        }
        else
        {
            // $font_size    = 16;
            $x = rand(0, $img_width/($length/1.5));
            $y = $font_size+2;
        }

        for ($i = 0; $i < strlen($word); $i++)
        {
            if ($use_font == FALSE)
            {
                $y = rand(0 , $img_height/2);
                imagestring($im, $font_size, $x, $y, substr($word, $i, 1), $text_color);
                $x += ($font_size*2);
            }
            else
            {        
                $y = rand($img_height/2, $img_height-3);
                imagettftext($im, $font_size, $angle, $x, $y, $text_color, $font_path, substr($word, $i, 1));
                $x += $font_size;
            }
        }
        

        // -----------------------------------
        //  Create the border
        // -----------------------------------

        imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $border_color);        

        // -----------------------------------
        //  Generate the image
        // -----------------------------------
        
        $img_name = $now.'.jpg';

        ImageJPEG($im, $img_path.$img_name);
        
        $img = "<img src=\"$img_url$img_name\" width=\"$img_width\" height=\"$img_height\" style=\"border:0;\" alt=\" \" />";
        
        ImageDestroy($im);
            
        return array('word' => $word, 'time' => $now, 'image' => $img);
    }
}

/* End of file captcha.php */
/* Location: ./obullo/helpers/captcha.php */