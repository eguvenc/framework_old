<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Http Response
    | -------------------------------------------------------------------
    | Sets default response headers.
    |
    | Prototype:
    |
    |   $this->response->json(array $data, 'default'];
    */
    'headers' => [

        'json' => [

            'default' => [
                'Cache-Control: no-cache, must-revalidate',
                'Expires: Mon, 26 Jul 1997 05:00:00 GMT',
                'Content-type: application/json;charset=UTF-8',
            ]
        ],
    ],

    /**
     * Output Compression
     *
     * Enables Gzip output compression for faster page loads.  When enabled,
     * the Response class will test whether your server supports Gzip.
     * Even if it does, however, not all browsers support compression
     * so enable only if you are reasonably sure your visitors can handle it
     */
    'compress' => [
        'enabled' => false,
    ],
);