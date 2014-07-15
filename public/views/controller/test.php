<?php

/**
 * $c Test Controller
 *
 * @var Test Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
    }
);

$app->func(
    'index',
    function () {

        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Global Request Object: <span class="string">'.$this->request->globals('uri')->getUriString().'</span></pre>';
        echo '<p>-----------------------------------------</p>';
    }
);

/* End of file test.php */
/* Location: .public/views/controller/test.php */