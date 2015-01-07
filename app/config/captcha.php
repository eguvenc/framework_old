<?php

return array(

    'driver' => 'cool',                         // Set default driver ( "secure" or "cool" ).
    
    'security' => array(
        'waveImage' => true                     // Image wave for more strong captchas.
    ),

    'locale' => array(
        'charset' => 'UTF-8'
    ),

    'characters' => array(
        'default' => array(
            'pool' => 'random'                  // Pools: numbers - alpha - random
        ),
        'pools' => array(
            'numbers' => '23456789',
            'alpha'   => 'ABCDEFGHJKLMNPRSTUVWXYZ',
            'random'  => '23456789ABCDEFGHJKLMNPRSTUVWXYZ'
        ),
        'length' => 5                           // Character length of captcha code
    ),

    'font' => array(
        'size' => 20,                           // Font size
        'path' => '/assets/fonts',              // Set captcha font path
    ),

    'image' => array(
        'type'       => 'png',                  // Set image extension
        // 'wave'       => true,                // Image wave for more strong captchas.
        'path'       => '/data/temp/captcha',   // Set captcha image path
        'height'     => 20,                     // Height of captcha image, "width = auto" no need to set it.
        'expiration' => 10,                     // Expiration time of captcha ( second )
    ),

    'colors' => array(                          // Color Schema
        'red'    => '255,0,0',
        'blue'   => '0,0,255',
        'green'  => '0,102,0',
        'black'  => '0,0,0',
        'yellow' => '255,255,0',
        'cyan'   => '0,146,134',
    ),

    'input' => array(
        'name' => '',
        'id'   => 'captcha_id'                  // Set captcha_id
    ),
    'text' => array(
        'colors' =>  array(
            'text' => array('cyan'),            // If its more than one produce random colors
            'noise' => array('cyan')            // If its more than one produce random noise colors
        )
    ),

    // 'driver' => 'cool',                         // Set default driver ( "secure" or "cool" ).
    // 'captcha_id'     => 'captcha_id',           // captcha_id set
    // 'set_pool'       => 'random',               // Pools: numbers - alpha - random
    // 'char'           => '5',                    // Character length of captcha code

    // 'charset' => 'UTF-8',
    // 'font_size'      => '20',                   // Font size
    // 'height'         => '20',                   // Height of captcha image, "width = auto" no need to set it.
    // 'wave_image'     => true,                   // Image wave for more strong captchas.
    // 'img_path'       => '/data/temp/captcha',   // Set captcha image path
    // 'image_type'     => 'png',                  // Set image extension
    // 'user_font_path' => '/assets/fonts',        // Set captcha font path
    // 'expiration'     => '10',                   // Expiration time of captcha ( second )
    // 'colors' => array(                          // Color Schema
    //                     'red'    => '255,0,0',
    //                     'blue'   => '0,0,255',
    //                     'green'  => '0,102,0',
    //                     'black'  => '0,0,0',
    //                     'yellow' => '255,255,0',
    //                     'cyan'   => '0,146,134',
    //                 ),

    // Captcha text color
    // 'defaultTextColor' => array('cyan'),  // If its more than one produce random colors
    // Background noise color
    // 'defaultNoiseColor'=> array('cyan'),  // If its more than one produce random noise colors
    // Characters
    // 'char_pool'          => array(
    //                             'numbers' => '23456789',
    //                             'alpha'   => 'ABCDEFGHJKLMNPRSTUVWXYZ',
    //                             'random'  => '23456789ABCDEFGHJKLMNPRSTUVWXYZ'
    //                             ),
    // Defined Fonts
    // 'fonts' => array(
    //     'AlphaSmoke'             => 'AlphaSmoke.ttf',
    //     'Anglican'               => 'Anglican.ttf',
    //     'Bknuckss'               => 'Bknuckss.ttf',
    //     'KingthingsFlashbang'    => 'KingthingsFlashbang.ttf',
    //     'NightSkK'               => 'NightSkK.ttf',
    //     'Notjustatoy'            => 'Notjustatoy.ttf',
    //     'Popsf'                  => 'Popsf.ttf',
    //     'SurreAlfreak'           => 'SurreAlfreak.ttf',
    //     'PrimerSpikedNormal'     => 'PrimerSpikedNormal.ttf',
    //     'Almontew'               => 'Almontew.ttf',
    //     'Hulk3d2'                => 'Hulk3d2.ttf',
    //     'Snagmag'                => 'Snagmag.ttf',
    //     'Seraphim'               => 'Seraphim.ttf',
    //     'SnowCapsExtendedNormal' => 'SnowCapsExtendedNormal.ttf',
    //     'VoltageNormal'          => 'VoltageNormal.ttf',
    //     'Antibiotech'            => 'Antibiotech.ttf',
    //     'Mcklb'                  => 'Mcklb.ttf',
    //     'Lows'                   => 'Lows.ttf',
    //     'Mcklm'                  => 'Mcklm.ttf',
    //     'Mclklsh'                => 'Mclklsh.ttf',
    //     'Osselets'               => 'Osselets.ttf',
    //     'StencilSansNormal'      => 'StencilSansNormal.ttf',
    //     'Eastersunrise'          => 'Eastersunrise.ttf',
    //     'Heras'                  => 'Heras.ttf',
    //     'Zebrt'                  => 'Zebrt.ttf',
    //     'GremlinSolid'           => 'GremlinSolid.ttf',
    //     'Entangle'               => 'Entangle.ttf',
    //     'Graffonti'              => 'Graffonti.ttf',
    //     'Searfont'               => 'Searfont.ttf',
    //     'Rollogli'               => 'Rollogli.ttf',
    //     'Mklt'                   => 'Mklt.ttf',
    //     'Antfgc'                 => 'Antfgc.ttf',
    //     'Riotact'                => 'Riotact.ttf',
    //     'Dexgothn'               => 'Dexgothn.ttf',
    //     'Guevara'                => 'Guevara.ttf',
    //     'Gastada'                => 'Gastada.ttf',
    //     'Decorette'              => 'Decorette.ttf',
    //     'Miscp'                  => 'Miscp.ttf',
    //     'Coulures'               => 'Coulures.ttf',
    //     'Cheapink'               => 'Cheapink.ttf',
    //     'LeafyGlade'             => 'LeafyGlade.ttf',
    //     'GremlinNormal'          => 'GremlinNormal.ttf',
    //     'Mcklw'                  => 'Mcklw.ttf',
    //     'Fordd'                  => 'Fordd.ttf',
    //     'Robokid'                => 'Robokid.ttf',
    //     'Xtrusion'               => 'Xtrusion.ttf',
    //     'AutumnGifts'            => 'AutumnGifts.ttf',
    //     'Mcklst'                 => 'Mcklst.ttf'
    // ),
);

/* End of file captcha.php */
/* Location: .app/config/captcha.php */