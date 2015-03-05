<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Response config
    | -------------------------------------------------------------------
    | Sets default response headers.
    |
    */
    'headers' => array(

        'json' => array(    // $this->response->json(array $data, 'default');

            'default' => [
                'Cache-Control: no-cache, must-revalidate',
                'Expires: Mon, 26 Jul 1997 05:00:00 GMT',
                'Content-type: application/json;charset=UTF-8',
            ]
        ),

        'xml' => array(    // $this->response->xml(array $data, 'default');

            'default'=> [
                "Content-type: text/xml",
            ]
        )
    )

);

/* End of file response.php */
/* Location: .app/config/response.php */