<?php

namespace Workers;

use Obullo\Queue\Job;
use Obullo\Queue\JobInterface;
use Obullo\Mailer\Transport\Smtp;
use Obullo\Mailer\Transport\Mandrill;
use Obullo\Container\ContainerInterface;

 /**
 * Mail Worker
 *
 * @category  Workers
 * @package   Mailer
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 * @link      http://obullo.com/docs/queue
 */
class Mailer implements JobInterface
{
    /**
     * Application
     * 
     * @var object
     */
    protected $c;

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
    }

    /**
     * Fire the job
     * 
     * @param Job   $job  object
     * @param array $data data array
     * 
     * @return void
     */
    public function fire($job, $data)
    {
        switch ($data['mailer']) { 
        case 'mandrill': 
            $this->sendWithMandrill($data);
            break;

        case 'smtp':
            $this->sendWithSmtp($data);
            break;
        }
        if ($job instanceof Job) {
            $job->delete(); 
        }       
    }

    /**
     * Sent mail with Mandrill Api
     * 
     * @param array $data queue data
     * 
     * @return void
     */
    protected function sendWithMandrill($data)
    {
        $mail = new Mandrill($this->c);

        $mail->setMailType($data['mailtype']);
        $mail->from($data['from_email'], $data['from_name']);

        foreach ($data['to'] as $to) {  // Parse to, cc and bcc 
            $method = $to['type'];
            $mail->$method($to['name'].' <'.$to['email'].'>');
        }
        $mail->subject($data['subject']);
        $mail->message($data[$mail->getMailType()]);

        if (isset($data['attachments'])) {
            foreach ($data['attachments'] as $attachments) {
                $mail->attach($attachments['fileurl'], 'attachment');
            }
        }
        if (isset($data['images'])) {
            foreach ($data['images'] as $attachments) {
                $mail->attach($attachments['fileurl'], 'inline');
            }
        }
        $mail->addMessage('send_at', $mail->setDate($data['send_at']));
        $mail->send();

        // echo $mail->printDebugger();  // Run debugger to see details.
    }

    /**
     * Sent mail with Smtp
     * 
     * @param array $data queue data
     * 
     * @return void
     */
    protected function sendWithSmtp($data)
    {
        $mail = new Smtp($this->c);
        $mail->from($data['from_email']);

        foreach ($data['to'] as $to) { // Parse to, cc and bcc 
            $method = $to['type'];
            $mail->$method($to['name'].' <'.$to['email'].'>');
        }
        $mail->subject($data['subject']);
        $mail->message($data[$mail->getMailType()]);

        if (isset($data['attachments'])) {
            foreach ($data['attachments'] as $attachments) {
                $mail->attach($attachments['fileurl'], 'attachment');
            }
        }
        if (isset($data['images'])) {
            foreach ($data['images'] as $attachments) {
                $mail->attach($attachments['fileurl'], 'inline');
            }
        }
        $mail->send();

        // echo $mail->printDebugger();  // Run debugger to see details.
    }

}

/* INCOMING DATA
{
    "mailer": "mandrill",
    'mailtype': "html", // text
    "html": "<p>Example HTML content</p>",
    "text": "Example text content",
    "subject": "example subject",
    "from_email": "message.from_email@example.com",
    "from_name": "Example Name",
    "to": [
        {
            "email": "recipient.email@example.com",
            "name": "Recipient Name",
            "type": "to"
        }
    ],
    "headers": {
        "Reply-To": "message.reply@example.com"
    },
    "important": false,
    "tags": [
        "password-resets"
    ],
    "attachments": [
        {
            "type": "text/plain",
            "name": "myfile.txt",
            "fileurl" : "/var/www/images/myfile.txt"
        }
    ],
    "images": [
        {
            "type": "image/png",
            "name": "myimages.gif",
            "fileurl": "http://example.com/static/myimages.gif"
        }
    ]
},
"send_at": "date"
}
*/