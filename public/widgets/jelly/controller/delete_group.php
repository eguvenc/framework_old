<?php

/**
 * $app delete element
 * 
 * @var Controller
 */
$app = new Controller(
    function ($c) {
        $c->load('url');
        $c->load('db');
        $c->load('get');
        $c->load('session/flash as flash');
        $c->load('service/jelly/form as jellyForm');
        $c->load('user/agent');
    }
);

$app->func(
    'index',
    function ($primaryKey) {
        $e = $this->db->transaction(
            function () use ($primaryKey) {
                $this->jellyForm->deleteFormGroup($primaryKey);
            }
        );
        if ($e === true) {
            $this->flash->set(array('notice' => 'Form group successfully deleted.', 'status' => NOTICE_SUCCESS));
        } else {
            $this->flash->set(array('notice' => $e->getMessage(), 'status' => NOTICE_ERROR));
        }
        $this->url->redirect($this->userAgent->getReferrer());
    }
);

/* End of file delete_element.php */
/* Location: .public/jform/controller/delete_element.php */