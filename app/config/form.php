<?php

return array(

    /*
    | -------------------------------------------------------------------
    | Form
    | -------------------------------------------------------------------
    | This file contains form messages configurations.
    | It is used by Form Class to set messages HTML template or attributes.
    | Array keys are predefined in form class file.
    |
    | Note : Default CSS classes brought from getbootstrap.com
    |
    */
    // Message key

    'message' => '<div class="{class}">{icon}{message}</div>',

    // Error
    0  => [
        'class' => 'alert alert-danger', 'icon' => '<span class="glyphicon glyphicon-remove-sign"></span> '
    ],
    // Success
    1 => [
        'class' => 'alert alert-success', 'icon' => '<span class="glyphicon glyphicon-ok-sign"></span> '
    ],
    // Warning
    2 => [
        'class' => 'alert alert-warning', 'icon' => '<span class="glyphicon glyphicon-exclamation-sign"></span> '
    ],
    // Info
    3 => [
        'class' => 'alert alert-info', 'icon' => '<span class="glyphicon glyphicon-info-sign"></span> '
    ],
);

/* End of file form.php */
/* Location: .app/config/form.php */