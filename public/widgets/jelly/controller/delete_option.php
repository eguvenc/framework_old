<?php

/**
 * $app delete form
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('db');
        $c->load('get');
        $c->load('session/flash as flash');
        $c->load('jelly/form');
        $c->load('user/agent');
    }
);

$app->func(
    'index',
    function ($primaryKey) {
        $e = $this->db->transaction(
            function () use ($primaryKey) {
                $this->jellyForm->deleteFormOption($primaryKey);
            }
        );
        if ($e === true) {
            $this->flash->set(array('notice' => 'Form option successfully deleted.', 'status' => NOTICE_SUCCESS));
        } else {
            $this->flash->set(array('notice' =>  $e->getMessage(), 'status' => NOTICE_ERROR));
        }
        $this->url->redirect($this->userAgent->getReferrer());
    }
);

/* End of file delete_form.php */
/* Location: .public/jform/controller/delete_form.php */