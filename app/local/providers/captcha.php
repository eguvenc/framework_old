<?php

return array(
    
    'params' => [

        'locale' => [
            'charset' => 'utf-8'
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
        ],
        'text' => [
            'colors' =>  [
                'text' => ['red'],
                'noise' => ['red']
            ]
        ]
    ],
    
    'methods' => [
        ['name' => 'setMod','argument' => ['secure']],
        ['name' => 'setPool','argument' => ['alpha']],
        ['name' => 'setChar','argument' => [5]],
        ['name' => 'setFont','argument' => ['NightSkK','AlphaSmoke','Popsf']],
        ['name' => 'setFontSize','argument' => [20]],
        ['name' => 'setHeight','argument' => [36]],
        ['name' => 'setWave','argument' => [false]],
        ['name' => 'setColor','argument' => ['red', 'black']],
        ['name' => 'setTrueColor','argument' => [false]],
        ['name' => 'setNoiseColor','argument' => ['red']],
    ]
);