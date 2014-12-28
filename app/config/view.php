<?php

return array(

    'layouts' => array(

        'default' => function () {
            $this->assign('header', '@layer.views/header');
            $this->assign('sidebar', '@layer.views/sidebar');
            $this->assign('footer', '@layer.views/footer');
        },
        'welcome' => function () {
            $this->assign('footer', $this->template('footer'));
        },
        
    )
);

/* End of file view.php */
/* Location: .app/config/view.php */