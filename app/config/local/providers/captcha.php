<?php

return array(
    
    'params' => [
    
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
            'truecolor'  => true,
            'type'       => 'png',
            'wave'       => true,
            'height'     => 80,
            'expiration' => 1440,
            'colors' =>  [
                'text' => ['red'],
                'noise' => ['red']
            ]
        ],
        'colors' => [
            'red'    => '255,0,0',
            'blue'   => '0,0,255',
            'green'  => '0,102,0',
            'black'  => '0,0,0',
            'yellow' => '255,255,0',
            'cyan'   => '0,146,134',
        ],
    ]
);