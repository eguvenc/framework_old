<?php

return array(
    
    'url' => 'https://mandrillapp.com/api/1.0/messages/send.json',
    'key' => $c['env']['MANDRILL_API_KEY'],  // Mandrill api key
    'pool' => 'Main Pool',                   // The name of the dedicated ip pool that should be used to send the message.
);

/* End of file smtp.php */
/* Location: .app/config/env.local/mailer/smtp.php */