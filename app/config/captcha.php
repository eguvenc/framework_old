<?php

/*
| -------------------------------------------------------------------
| CAPTCHA
| -------------------------------------------------------------------
| This file specifies captcha package default settings: colors,
| driver, expiration, image height, image font, image wave.
|
| -------------------------------------------------------------------
| Prototype
| -------------------------------------------------------------------
| 
|	$captcha['driver'] = 'cool';	
|
*/
    
$captcha['driver']        		= 'cool';						// Set default driver ( "secure" or "cool" ).
$captcha['set_pool']      		= "random";						// Pools: numbers - alpha - random
$captcha['char']          		= '15';							// Character length of captcha code
$captcha['font_size']     		= '25';							// Font size
$captcha['height']        		= '50';							// Height of captcha image, width = auto.
$captcha['wave_image']    		= true;							// Image wave for more strong captchas.
$captcha['img_path']       		= '/data/temp/captcha';			// Set captcha image path
$captcha['user_font_path'] 		= '/assets/fonts';				// Set captcha font path
$captcha['image_type'] 	  		= 'gif';						// Set image extension
$captcha['expiration'] 	  		= '2';							// Expiration time of captcha

$captcha['colors'] 				= array(
									'red'    => '255,0,0',
									'blue'   => '0,0,255',
									'green'  => '0,102,0',
									'black'  => '0,0,0',
									'yellow' => '255,255,0',
									'cyan'   => '0,146,134',
								);

$captcha['default_text_color']  = array('cyan'); 	// If its more than one produce random colors
$captcha['default_noise_color'] = array('cyan');	// If its more than one produce random noise colors

$captcha['char_pool']           = array(
								'numbers' => '23456789',
								'alpha'   => 'ABCDEFGHJKLMNPRSTUVWXYZ',
								'random'  => '23456789ABCDEFGHJKLMNPRSTUVWXYZ'
								);

$captcha['fonts']              = array(
								'AlphaSmoke'             => 'AlphaSmoke.ttf',
								'Anglican'               => 'Anglican.ttf',
								'Bknuckss'               => 'Bknuckss.ttf',
								'KingthingsFlashbang'    => 'KingthingsFlashbang.ttf',
								'NightSkK'               => 'NightSkK.ttf',
								'Notjustatoy'            => 'Notjustatoy.ttf',
								'Popsf'                  => 'Popsf.ttf',
								'SurreAlfreak'           => 'SurreAlfreak.ttf',
								'PrimerSpikedNormal'     => 'PrimerSpikedNormal.ttf',
								'Almontew'               => 'Almontew.ttf',
								'Hulk3d2'                => 'Hulk3d2.ttf',
								'Snagmag'                => 'Snagmag.ttf',
								'Seraphim'               => 'Seraphim.ttf',
								'SnowCapsExtendedNormal' => 'SnowCapsExtendedNormal.ttf',
								'VoltageNormal'          => 'VoltageNormal.ttf',
								'Antibiotech'            => 'Antibiotech.ttf',
								'Mcklb'                  => 'Mcklb.ttf',
								'Lows'                   => 'Lows.ttf',
								'Mcklm'                  => 'Mcklm.ttf',
								'Mclklsh'                => 'Mclklsh.ttf',
								'Osselets'               => 'Osselets.ttf',
								'StencilSansNormal'      => 'StencilSansNormal.ttf',
								'Eastersunrise'          => 'Eastersunrise.ttf',
								'Heras'                  => 'Heras.ttf',
								'Zebrt'                  => 'Zebrt.ttf',
								'GremlinSolid'           => 'GremlinSolid.ttf',
								'Entangle'               => 'Entangle.ttf',
								'Graffonti'              => 'Graffonti.ttf',
								'Searfont'               => 'Searfont.ttf',
								'Rollogli'               => 'Rollogli.ttf',
								'Mklt'                   => 'Mklt.ttf',
								'Antfgc'                 => 'Antfgc.ttf',
								'Riotact'                => 'Riotact.ttf',
								'Dexgothn'               => 'Dexgothn.ttf',
								'Guevara'                => 'Guevara.ttf',
								'Gastada'                => 'Gastada.ttf',
								'Decorette'              => 'Decorette.ttf',
								'Miscp'                  => 'Miscp.ttf',
								'Coulures'               => 'Coulures.ttf',
								'Cheapink'               => 'Cheapink.ttf',
								'LeafyGlade'             => 'LeafyGlade.ttf',
								'GremlinNormal'          => 'GremlinNormal.ttf',
								'Mcklw'                  => 'Mcklw.ttf',
								'Fordd'                  => 'Fordd.ttf',
								'Robokid'                => 'Robokid.ttf',
								'Xtrusion'               => 'Xtrusion.ttf',
								'AutumnGifts'            => 'AutumnGifts.ttf',
								'Mcklst'                 => 'Mcklst.ttf'
								);

/* End of file captcha.php */
/* Location: .app/config/captcha.php */