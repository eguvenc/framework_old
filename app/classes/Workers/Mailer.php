<?php

namespace Workers;

use Obullo\Queue\Job,
    Obullo\Queue\JobInterface,
    Obullo\Mail\Send\Smtp,
    Obullo\Mail\Transport\Mandrill;

 /**
 * Mail Worker
 *
 * @category  Workers
 * @package   Mailer
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/docs/queue
 */
Class Mailer implements JobInterface
{
    /**
     * Container
     * 
     * @var object
     */
    protected $c;

    /**
     * Config parameters
     * 
     * @var array
     */
    protected $config;

    /**
     * Constructor
     * 
     * @param object $c container
     */
    public function __construct($c)
    {
        $this->c = $c;
        $this->config = $c['config']->load('mail');
    }

    /**
     * Fire the job
     * 
     * @param Job   $job  object
     * @param array $data data array
     * 
     * @return void
     */
    public function fire(Job $job, $data)
    {
        $data = $data['message'];

        switch ($data['mailer']) {

        case 'mandrill':
            $mail = new Mandrill($this->c, $this->config);

            $mail->setMailType($data['mailtype']);
            $mail->from($data['from_email'], $data['from_name']);

            foreach ($data['to'] as $to) {
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

            // print_r($mail->response()->getArray());
            // echo $mail->printDebugger();
            break;

        case 'smtp':

            break;
        }
        /**
         * Delete job from queue after successfull operation.
         */
        $job->delete(); 
            
    }
}

/* PUSH DATA
    {
        "message": {
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

/* End of file Mailer.php */
/* Location: .app/classes/Mailer.php */