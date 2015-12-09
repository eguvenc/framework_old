<?php

return array(
    
    'params' => [

        'locale' => [
            'charset' => 'UTF-8'
        ],
        'characters' => [
            'default' => [
                'pool' => 'random'
            ],
            'pools' => [
                'numbers' => '23456789',
                'alpha'   => 'ABCDEFGHJKLMNPRSTUVWXYZ',
                'random'  => '23456789ABCDEFGHJKLMNPRSTUVWXYZ'
            ],
            'length' => 5
        ],
        'font' => [
            'size' => 30,
            'path' => '/assets/fonts',
        ],
        'image' => [
            'trueColor'  => true,
            'type'       => 'png',
            'wave'       => true,
            'height'     => 80,
            'expiration' => 1440,
        ],
        'colors' => [
            'red'    => '255,0,0',
            'blue'   => '0,0,255',
            'green'  => '0,102,0',
            'black'  => '0,0,0',
            'yellow' => '255,255,0',
            'cyan'   => '0,146,134',
        ],
        'form' => [
            'input' => [
                'attributes' => [
                    'type'  => 'text',
                    'name'  => 'captcha_answer',
                    'class' => 'form-control',
                    'id'    => 'captcha_answer',      
                    'placeholder' => 'Security Code'
                ]
            ],
            'img' => [
                'attributes' => [             
                    'src'   =>  '/captcha/create/index/',
                    'style' => 'display:block;float:left;margin-right:10px;margin-top:10px;',
                    'id'    => 'captcha_image',
                    'class' => ''
                ]
            ],
            'refresh' => [
                'button' => '<button type="button" style="margin-top:10px;" onclick="refreshCaptcha(this.form);" class="btn btn-default" aria-label="Left Align">
                  <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
                  Refresh
                </button>',
            ],
            'validation' => [
                'callback' => true,
            ]
        ],
        'text' => [
            'colors' =>  [
                'text' => ['red'],
                'noise' => ['red']
            ]
        ],
        'fonts' => [
            'AlphaSmoke'             => 'AlphaSmoke.ttf',
            'Anglican'               => 'Anglican.ttf',
            'Bknuckss'               => 'Bknuckss.ttf',
            'KingthingsFlashbang'    => 'KingthingsFlashbang.ttf',
            'NightSkK'               => 'NightSkK.ttf',
            'Notjustatoy'            => 'Notjustatoy.ttf',
            'Popsf'                  => 'Popsf.ttf',
            'SurreAlfreak'           => 'SurreAlfreak.ttf',
        ],
    ],

    'methods' => [
        'setParameters' => [
            'setMod' => 'secure',
            'setPool' => 'alpha',
            'setChar' => 5,
            'setFont' => ['NightSkK','AlphaSmoke','Popsf'],
            'setFontSize' => 20,
            'setHeight' => 36,
            'setWave' => false,
            'setColor' => ['red', 'black'],
            'setTrueColor' => false,
            'setNoiseColor' => ['red'],
        ]
    ]
);