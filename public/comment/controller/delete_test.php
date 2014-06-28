<?php

/**
 * $c delete
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('form');
        $c->load('hvc');
    }
);

$c->func(
    'index',
    function ($id) {
        
        new Unit_Test;

        // TEST DATABASE NOT

        // $r = $this->hvc->delete('private/comments/delete/', array('id' => $id));

        $this->unit->run($r['success'], true, 'delete comment');

    }
);
