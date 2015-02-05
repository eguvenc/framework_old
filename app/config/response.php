<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Response config
    | -------------------------------------------------------------------
    | Sets default response headers and others.
    |
    */
    'headers' => array(

        'json' => array(    // $this->response->json($data, 'default');  method use below the default headers.

            'default' => [
                'Cache-Control: no-cache, must-revalidate',
                'Expires: Mon, 26 Jul 1997 05:00:00 GMT',
                'Content-type: application/json;charset=UTF-8',
            ]
        )
    )

);

/* End of file response.php */
/* Location: .app/config/response.php */