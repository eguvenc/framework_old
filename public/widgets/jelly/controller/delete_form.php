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
        $c->load('service/jelly/form as jellyForm');
        $c->load('user/agent');
    }
);

$app->func(
    'index',
    function ($formId) {
        $e = $this->db->transaction(
            function () use ($formId) {
                $this->jellyForm->deleteForm($formId);
            }
        );
        if ($e === true) {
            $this->flash->set(array('notice' => 'Form successfully deleted.', 'status' => NOTICE_SUCCESS));
        } else {
            $this->flash->set(array('notice' => $e->getMessage(), 'status' => NOTICE_ERROR));
        }
        $this->url->redirect($this->userAgent->getReferrer());
    }
);

/* End of file delete_form.php */
/* Location: .public/jform/controller/delete_form.php */