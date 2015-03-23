<?php

return array(
    
    'host' => 'smtp.mandrillapp.com',          // SMTP Server Address.
    'user' => $c['env']['MANDRILL_USERNAME'],  // SMTP Username.
    'pass' => $c['env']['MANDRILL_API_KEY'],   // SMTP Password.
    'port' => '587',                           // Port.
    'timeout' => '5',                          // Timeout (in seconds).
);

/* End of file smtp.php */
/* Location: .app/config/env/local/mailer/smtp.php */