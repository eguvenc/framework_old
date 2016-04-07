<?php

return array(
    
    'params' => [

        'locale' => [
            'lang' => 'en'
        ],
        'api' => [
            'key' => [
                'site' => '6LcWtwUTAAAAACzJjC2NVhHipNPzCtjKa5tiE6tM',
                'secret' => '6LcWtwUTAAAAAEwwpWdoBMT7dJcAPlborJ-QyW6C',
            ]
        ],
        'user' => [
            'autoSendIp' => false
        ],
        'form' => [
            'input' => [
                'attributes' => [
                    'name' => 'recaptcha',
                    'id' => 'recaptcha',
                    'type' => 'text',
                    'value' => 1,
                    'style' => 'display:none;',
                ]
            ]
        ]
    ],
    'methods' => [
        ['name' => 'setLang', 'argument' => ['en']],
    ]
);