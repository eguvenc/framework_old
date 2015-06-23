<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Http Response Configuration
    | -------------------------------------------------------------------
    | Sets default response headers.
    |
    */
    'headers' => [

        'json' => [    // $this->response->json(array $data, 'default'];

            'default' => [
                'Cache-Control: no-cache, must-revalidate',
                'Expires: Mon, 26 Jul 1997 05:00:00 GMT',
                'Content-type: application/json;charset=UTF-8',
            ]
        ],

        'xml' => [    // $this->response->xml(array $data, 'default'];

            'default'=> [
                "Content-type: text/xml",
            ]
        ]
    ],

    'compress' => [
        'enabled' => false,   // Enables Gzip output compression for faster page loads.  When enabled,
    ],                        // the Response class will test whether your server supports Gzip.
                              // Even if it does, however, not all browsers support compression
                              // so enable only if you are reasonably sure your visitors can handle it
);

/* End of file response.php */
/* Location: .config/response.php */